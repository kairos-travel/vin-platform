<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegisteredUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(StoreRegisteredUserRequest $request): RedirectResponse|JsonResponse
    {
        $data = $request->validated();
        $user = User::create($data);

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json([
                'name' => $user->name ?? $user->login ?? $user->email,
                'dashboard_url' => route('dashboard', absolute: false),
                'csrf_token' => csrf_token(),
            ]);
        }

        return redirect(route('main', absolute: false));
    }
}
