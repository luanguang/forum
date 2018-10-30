<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Auth\Events\Registered;
use App\Model\User;

class RegistrationTest extends TestCase
{
    public function test_a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        event(new Registered(create('User')));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    public function test_user_can_fully_confirm_their_email_addresses()
    {
        $this->post('/register', [
            'name'                  => 'NoNo1',
            'email'                 => 'NoNo1@example.com',
            'password'              => '123456',
            'password_confirmation' => '123456'
        ]);

        $user = User::whereName('NoNo1')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);
        $response->assertRedirect('/threads');
    }
}
