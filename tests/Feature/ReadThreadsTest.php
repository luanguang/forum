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

    public function test_a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = create('Thread');
        create('Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('Thread');
        create('Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithTwoReplies = $this->thread;

        $response = $this->getJson('threads?popularity=1')->json();

        $this->get('threads?popularity=1')->assertStatus(200);
    }

    public function test_a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create('Thread');
        create('Reply', ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertEquals(40, $response['total']); //数量不管
    }

    public function test_a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = create('Thread');
        create('Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response);
    }

    public function test_we_record_a_new_visit_each_time_the_thread_is_read()
    {
        $thread = create('Thread');

        $this->assertSame(0, $thread->visits);

        $this->call('GET', $thread->path());

        $this->assertEquals(1, $thread->fresh()->visits);
    }
}
