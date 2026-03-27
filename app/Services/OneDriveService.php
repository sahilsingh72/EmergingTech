<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class OneDriveService
{
    protected $clientId;
    protected $clientSecret;
    protected $tenantId;
    protected $redirectUri;
    protected $scopes;
    protected $tokenFile;

    public function __construct()
    {
        $this->clientId     = config('services.onedrive.client_id');
        $this->clientSecret = config('services.onedrive.client_secret');
        $this->tenantId     = config('services.onedrive.tenant_id');
        // $this->redirectUri  = config('services.onedrive.redirect');
        $this->redirectUri  = config('services.onedrive.redirect_uri');
        $this->scopes       = 'offline_access Files.ReadWrite User.Read';
        $this->tokenFile    = storage_path('app/onedrive_token.json');
    }

    protected function getAuthority()
    {
        return "https://login.microsoftonline.com/{$this->tenantId}";
    }

    // Get authorization URL to log in first time
    public function getAuthUrl()
    {
        return $this->getAuthority() . "/oauth2/v2.0/authorize?" . http_build_query([
            'client_id'     => $this->clientId,
            'response_type' => 'code',
            'redirect_uri'  => $this->redirectUri,
            'response_mode' => 'query',
            'scope'         => $this->scopes,
        ]);
    }

    // Exchange code for token
    public function getTokenFromCode($code)
    {
        $response = Http::asForm()->post($this->getAuthority() . '/oauth2/v2.0/token', [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $code,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
            'scope'         => $this->scopes,
        ]);

        $tokens = $response->json();

        if (!isset($tokens['access_token'])) {
            throw new \Exception("Failed to get token: " . $response->body());
        }

        $tokens['expires_at'] = time() + ($tokens['expires_in'] ?? 3600) - 60;
        file_put_contents($this->tokenFile, json_encode($tokens, JSON_PRETTY_PRINT));

        return $tokens;
    }

    // Get access token (refresh if expired)
    protected function getAccessToken()
    {
        if (!file_exists($this->tokenFile)) {
            // throw new \Exception(" Run login flow first to get OneDrive token (use getAuthUrl()).");
            throw new HttpResponseException(
                Redirect::to('/onedrive/login')
            );
        }

        $tokens = json_decode(file_get_contents($this->tokenFile), true);
        if (!is_array($tokens)) {
            // Delete corrupted file
            @unlink($this->tokenFile);

            throw new HttpResponseException(
                Redirect::to('/onedrive/login')
                    ->with('error', 'Session expired. Please login again.')
            );
        }

        // If expires_at not present, compute it from expires_in (if available) or force refresh.
        if (!isset($tokens['expires_at'])) {
            if (isset($tokens['expires_in'])) {
                $tokens['expires_at'] = time() + intval($tokens['expires_in']) - 60;
            } else {
                // Force refresh by setting expiry in the past
                $tokens['expires_at'] = 0;
            }
            // persist the updated structure
            file_put_contents($this->tokenFile, json_encode($tokens, JSON_PRETTY_PRINT));
        }

        // If access_token missing or expired -> refresh
        if (!isset($tokens['access_token']) || time() >= intval($tokens['expires_at'])) {
            if (!isset($tokens['refresh_token'])) {
                // nothing to refresh with — must re-authenticate
                throw new \Exception("Refresh token missing. Please re-authenticate using the OAuth flow.");
            }

            $response = Http::asForm()->post($this->getAuthority() . '/oauth2/v2.0/token', [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $tokens['refresh_token'],
                'grant_type'    => 'refresh_token',
                'scope'         => $this->scopes,
            ]);

            $newTokens = $response->json();

            if (!is_array($newTokens) || isset($newTokens['error'])) {
                // Try to show helpful error
                $msg = is_array($newTokens) && isset($newTokens['error_description'])
                    ? $newTokens['error_description']
                    : $response->body();
                throw new \Exception("Refresh failed: " . $msg);
            }

            // If API did not return a refresh_token, keep old one (some responses omit it)
            if (!isset($newTokens['refresh_token']) && isset($tokens['refresh_token'])) {
                $newTokens['refresh_token'] = $tokens['refresh_token'];
            }

            // set expires_at and persist
            $newTokens['expires_at'] = time() + ($newTokens['expires_in'] ?? 3600) - 60;
            file_put_contents($this->tokenFile, json_encode($newTokens, JSON_PRETTY_PRINT));

            return $newTokens['access_token'];
        }

        // token is valid
        return $tokens['access_token'];
    }
    public function uploadDirect($file, $folder = "MyMedia")
    {
        $accessToken = $this->getAccessToken();

        $filename = $file->getClientOriginalName();
        $stream   = fopen($file->getRealPath(), 'r');

        $onedrivePath = "{$folder}/{$filename}";
        // $url = "https://graph.microsoft.com/v1.0/me/drive/root:/$onedrivePath:/content";
        $uploadUrl = "https://graph.microsoft.com/v1.0/me/drive/root:/$onedrivePath:/content";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type'  => $file->getMimeType() ?? 'application/octet-stream',
        ])
            ->timeout(120)
            ->send('PUT', $uploadUrl, [
                'body' => $stream,
            ]);

        fclose($stream);

        if (!$response->successful()) {
            throw new \Exception("OneDrive upload failed: " . $response->body());
        }

        // Create shareable link
        $linkResponse = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ])->post("https://graph.microsoft.com/v1.0/me/drive/root:/$onedrivePath:/createLink", [
            'type' => 'view',
            'scope' => 'anonymous',
        ]);

        $linkData = $linkResponse->json();
        $shareableUrl = $linkData['link']['webUrl'] ?? null;

        return [
            'path' => $onedrivePath,
            'url'  => $shareableUrl,
        ];
    }
    public function renameFolder($folderPath, $newName)
    {
        $info = $this->getFileInfo($folderPath);

        $folderId = $info['id'] ?? null;
        if (!$folderId) {
            throw new \Exception("Folder not found for rename.");
        }

        $accessToken = $this->getAccessToken();
        $response = Http::withToken($accessToken)
            ->patch("https://graph.microsoft.com/v1.0/me/drive/items/{$folderId}", [
                "name" => $newName
            ]);

        return $response->json();
    }

    public function getFileInfo($path)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->get("https://graph.microsoft.com/v1.0/me/drive/root:/$path");

        if ($response->successful()) {
            return $response->json();
        }

        if ($response->status() == 404) {
            return [
                'status' => false,
                'message' => "The requested file does not exist on OneDrive.",
            ];
        }

        // Any other OneDrive/Graph API error
        return [
            'status' => false,
            'message' => "Unable to fetch file info from OneDrive. Please try again later.",
            'error' => $response->json(),
        ];
    }
    public function deleteFile($path)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->delete("https://graph.microsoft.com/v1.0/me/drive/root:/$path");

        if ($response->successful()) {
            return true;
        }

        throw new \Exception('Failed to delete file from OneDrive');
    }
}
