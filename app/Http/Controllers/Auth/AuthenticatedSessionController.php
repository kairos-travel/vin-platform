<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
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
    public function store(LoginUserRequest $request): RedirectResponse|JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->wantsJson()) {
            $user = $request->user();

            return response()->json([
                'name' => $user->name ?? $user->login ?? $user->email,
                'dashboard_url' => route('dashboard', absolute: false),
                'csrf_token' => csrf_token(),
            ]);
        }

        return redirect()->intended(route('main', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'csrf_token' => csrf_token(),
            ]);
        }

        return redirect('/');
    }
}
