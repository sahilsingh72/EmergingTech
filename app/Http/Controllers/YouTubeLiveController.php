<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YouTubeLiveController extends Controller
{
    public function status()
    {
        $apiKey = config('services.youtube.key');
        $channelId = config('services.youtube.channel_id');

        $response = Http::get(
            'https://www.googleapis.com/youtube/v3/search',
            [
                'part'       => 'snippet',
                'channelId'  => $channelId,
                'type'       => 'video',
                'eventType'  => 'live',
                'key'        => $apiKey,
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'isLive' => "false",
            ]);
        }

        $items = $response->json('items');

        if (!empty($items)) {
            return response()->json([
                'isLive'  => true,
                'videoId' => $items[0]['id']['videoId'],
            ]);
        }

        return response()->json([
            'isLive' => false,
        ]);
    }

    public function multiZoneStatus()
    {
        $apiKey = config('services.youtube.key');
        $zones  = Zone::all();
        $result = [];

        foreach ($zones as $zone) {

            // STEP 1: FIND LIVE VIDEO ID (ONLY RELIABLE WAY)
            $searchRes = Http::get(
                'https://www.googleapis.com/youtube/v3/search',
                [
                    'part'      => 'id',
                    'channelId' => $zone->youtube_channel_id,
                    'eventType' => 'live',
                    'type'      => 'video',
                    'key'       => $apiKey,
                ]
            );

            $searchJson = $searchRes->json();

            if (
                $searchRes->successful() &&
                !empty($searchJson['items']) &&
                isset($searchJson['items'][0]['id']['videoId'])
            ) {
                $videoId = $searchJson['items'][0]['id']['videoId'];

                // STEP 2: CONFIRM LIVE STATUS
                $videoRes = Http::get(
                    'https://www.googleapis.com/youtube/v3/videos',
                    [
                        'part' => 'liveStreamingDetails',
                        'id'   => $videoId,
                        'key'  => $apiKey,
                    ]
                );

                $videoJson = $videoRes->json();

                if (
                    $videoRes->successful() &&
                    isset($videoJson['items'][0]['liveStreamingDetails'])
                ) {
                    $result[] = [
                        'zone_id'   => $zone->id,
                        'zone_name' => $zone->zone_name,
                        'isLive'    => true,
                        'videoId'   => $videoId,
                    ];
                    continue;
                }
            }

            // OFFLINE
            $result[] = [
                'zone_id'   => $zone->id,
                'zone_name' => $zone->zone_name,
                'isLive'    => false,
                'videoId'   => null,
            ];
        }

        return response()->json($result);
    }
//     public function multiZoneStatus()
//     {
//         $apiKey = config('services.youtube.key');
//         $zones = Zone::all();
//         $result = [];

//         foreach ($zones as $zone) {

//             // Search for live stream
//             $liveSearch = Http::get(
//             'https://www.googleapis.com/youtube/v3/search',
//             [
//                 'part'      => 'id',
//                 'channelId' => $zone->youtube_channel_id,
//                 'eventType' => 'live',
//                 'type'      => 'video',
//                 'key'       => $apiKey,
//             ]
//         );

//         if ($liveSearch->successful() && count($liveSearch->json('items')) > 0) {
//             $result[] = [
//                 'zone_id'   => $zone->id,
//                 'zone_name' => $zone->zone_name,
//                 'isLive'    => true,
//                 'videoId'   => $liveSearch->json('items.0.id.videoId'),
//             ];
//             continue;
//         }

//         // STEP 2: Check latest video liveBroadcastContent
//         $latest = Http::get(
//             'https://www.googleapis.com/youtube/v3/search',
//             [
//                 'part'       => 'snippet',
//                 'channelId'  => $zone->youtube_channel_id,
//                 'order'      => 'date',
//                 'maxResults' => 1,
//                 'type'       => 'video',
//                 'key'        => $apiKey,
//             ]
//         );

//         if ($latest->successful()) {
//             $item = $latest->json('items.0');

//             if (
//                 isset($item['snippet']['liveBroadcastContent']) &&
//                 $item['snippet']['liveBroadcastContent'] === 'live'
//             ) {
//                 $result[] = [
//                     'zone_id'   => $zone->id,
//                     'zone_name' => $zone->zone_name,
//                     'isLive'    => true,
//                     'videoId'   => $item['id']['videoId'],
//                 ];
//                 continue;
//             }
//         }

//         // OFFLINE
//         $result[] = [
//             'zone_id'   => $zone->id,
//             'zone_name' => $zone->zone_name,
//             'isLive'    => false,
//             'videoId'   => null,
//         ];
//     }

//     return response()->json($result);
// }
}