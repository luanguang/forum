<?php

namespace Tests\Feature;

use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    public function test_a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        $thread = create('Thread');

        $this->post($thread->path() . '/subscriptions');

        $thread->addReply([
            'user_id' => auth()->id(),
            'body'    => 'Some reply'
        ]);

        $this->assertCount(1, auth()->user()->notifications);
    }

    public function test_a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();

        $thread = create('Thread');

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
