<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Model\Thread')->create();
    }

    public function test_a_user_can_view_all_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_a_single_thread()
    {
        $this->get('/threads/'.$this->thread->id)->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory('App\Model\Reply')->create(['thread_id' => $this->thread->id]);
        $this->get('/threads/'.$this->thread->id)->assertSee($reply->body);
    }
}
