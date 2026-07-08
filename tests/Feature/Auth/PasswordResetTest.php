<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested_with_login_field(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->postJson('/forgot-password', [
            'login' => $user->email,
        ]);

        $response->assertOk();

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_reset_password_link_rejects_phone_login(): void
    {
        $response = $this->postJson('/forgot-password', [
            'login' => '+79991234567',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['login']);
    }

    public function test_reset_password_screen_opens_modal_on_main_page(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['login' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $response = $this->get('/reset-password/'.$notification->token.'?email='.urlencode($user->email));

            $response->assertOk();
            $response->assertSee('Новый пароль', false);
            $response->assertSee('authModal', false);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['login' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'login' => $user->email,
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            return true;
        });

        $this->assertTrue(Hash::check('new-secure-password', $user->refresh()->password));
    }

    public function test_password_can_be_reset_via_json(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['login' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $response = $this->postJson('/reset-password', [
                'token' => $notification->token,
                'login' => $user->email,
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ]);

            $response->assertOk()
                ->assertJsonStructure(['message']);

            return true;
        });

        $this->assertTrue(Hash::check('new-secure-password', $user->refresh()->password));
    }
}
