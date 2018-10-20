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

        $this->assertCount(1, $thread->fresh()->subscriptions);
    }

    public function test_a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();

        $thread = create('Thread');

        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
