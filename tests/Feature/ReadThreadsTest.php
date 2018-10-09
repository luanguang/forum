<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->thread = create('Thread');
    }

    public function test_a_user_can_view_all_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = create('Reply', ['thread_id' => $this->thread->id]);
        $this->get($this->thread->path())->assertSee($reply->body);
    }

    public function test_a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('Channel');
        $threadInChannel = create('Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('Thread');

        $this->get('threads/' . $channel->slug)
            ->assertStatus(200)
            ->assertDontSee($threadNotInChannel->title);
    }

    public function test_a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('User', ['name' => 'test']));

        $threadByTest = create('Thread', ['user_id' => auth()->id()]);
        $threadNotByTest = create('Thread');

        $this->get('/threads?by=test')
            ->assertStatus(200)
            ->assertDontSee($threadNotByTest->title);
    }
}
