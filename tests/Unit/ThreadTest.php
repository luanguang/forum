<?php

namespace Tests\Unit;

use Tests\TestCase;

class ThreadTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->thread = create('Thread');
    }

    public function test_a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\Model\User', $this->thread->creator);
    }

    public function test_a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body'    => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_belongs_to_a_channel()
    {
        $thread = create('Thread');

        $this->assertInstanceOf('App\Model\Channel', $thread->channel);
    }

    public function test_a_thread_can_make_a_string_path()
    {
        $thread = create('Thread');

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $thread = create('Thread');

        // $this->signIn();

        $thread->subscribe($user_id = 1);

        // $thread->subscriptions()->where('user_id', auth()->id())->get();
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $user_id)->count());
    }

    public function test_a_thread_can_be_unsubscribed_from()
    {
        $thread = create('Thread');

        $thread->subscribe($user_id = 1);

        $thread->unsubscribe($user_id);

        $this->assertCount(0, $thread->subscriptions);
    }
}
