<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $thread = create('Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body'    => 'Some reply'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('User')->id,
            'body'    => 'Some here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    public function test_a_user_can_fetch_their_unread_notifications()
    {
        factory(DatabaseNotification::class)->create();

        $this->assertCount(1, $this->getJson('/profile/' . auth()->user()->name . '/notifications')->json());
    }

    public function test_a_user_can_clear_a_notification()
    {
        factory(DatabaseNotification::class)->create();

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unreadNotifications);

            $this->delete('/profile/' . $user->name . '/notifications/' . $user->unreadNotifications->first()->id);

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
