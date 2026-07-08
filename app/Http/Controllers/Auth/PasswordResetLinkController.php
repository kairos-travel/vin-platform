<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(ForgotPasswordRequest $request): RedirectResponse|JsonResponse
    {
        $email = $request->email;

        if (! isset($email)) {
            $message = 'Пока восстановление пароля по телефону невозможно';

            if ($request->wantsJson()) {
                throw ValidationException::withMessages(['login' => $message]);
            }

            return back()->withErrors(['login' => $message]);
        }

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (TransportExceptionInterface|Throwable $exception) {
            report($exception);

            $message = 'Не удалось отправить письмо. Попробуйте позже.';

            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 503);
            }

            return back()->withInput($request->only('login'))
                ->withErrors(['login' => $message]);
        }

        if ($status !== Password::RESET_LINK_SENT) {
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

        return back()->with('status', __($status));
    }
}
