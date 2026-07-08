<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Http\Requests\Auth\StoreNewPasswordRequest;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('main.index', [
            'token' => $request->route('token'),
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(StoreNewPasswordRequest $request): RedirectResponse|JsonResponse
    {
        if (! $request->email) {
            $message = 'Пока восстановление пароля по телефону невозможно';

            if ($request->wantsJson()) {
                throw ValidationException::withMessages(['login' => $message]);
            }

            return back()->withErrors(['login' => $message]);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => $request->password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            if ($request->wantsJson()) {
                throw ValidationException::withMessages(['login' => __($status)]);
            }

            return back()->withInput($request->only('login'))
                ->withErrors(['login' => __($status)]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => __($status),
            ]);
        }

        return redirect()->route('login')->with('status', __($status));
    }
}
