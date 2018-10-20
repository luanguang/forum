<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotificationsTest extends TestCase
{
    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();

        $thread = create('Thread');

        $thread->subscribe();

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
        $this->signIn();

        $thread = create('Thread')->subscribe();

        $thread->addReply([
            'user_id' => create('User')->id,
            'body'    => 'some reply'
        ]);

        $user = auth()->user();

        $response = $this->getJson('/profile/' . $user->name . '/notifications')->json();

        $this->assertCount(1, $response);
    }

    public function test_a_user_can_clear_a_notification()
    {
        $this->signIn();

        $thread = create('Thread')->subscribe();

        $thread->addReply([
            'user_id' => create('User')->id,
            'body'    => 'here'
        ]);

        $user = auth()->user();

        $this->assertCount(1, $user->unreadNotifications);

        $notification_id = $user->unreadNotifications->first()->id;

        $this->delete('/profile/' . $user->name . "/notifications/{$notification_id}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
