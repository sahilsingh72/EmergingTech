<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected function authenticated($request, $user)
    {
        switch ($user->role->name) {
            case 'OCAC':
                return redirect()->route('ocac.dashboard');
            case 'OKCL':
                return redirect()->route('okcl.dashboard');
            case 'DLC':
                return redirect()->route('dlc.dashboard');
            case 'Institute':
                return redirect()->route('institute.dashboard');
            case 'Trainer':
                return redirect()->route('trainer.dashboard');
            default:
                return redirect('/');
        }
    }
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
