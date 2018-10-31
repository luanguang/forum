<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use App\Model\User;

class RegistrationTest extends TestCase
{
    public function test_confirming_an_invalid_token()
    {
        $this->get('/register/confirm?token=invalid')
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknow token');
    }

    public function test_a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name'                  => 'NoNo2',
            'email'                 => 'NoNo2@example.com',
            'password'              => '123456',
            'password_confirmation' => '123456'
        ]);

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    public function test_user_can_fully_confirm_their_email_addresses()
    {
        $this->post(route('register'), [
            'name'                  => 'NoNo1',
            'email'                 => 'NoNo1@example.com',
            'password'              => '123456',
            'password_confirmation' => '123456'
        ]);

        $user = User::whereName('NoNo1')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));

        tap($user, function ($user) {
            $this->assertTrue($user->fresh()->confirmed);
            $this->assertNull($user->fresh()->confirmation_token);
        });
    }
}
