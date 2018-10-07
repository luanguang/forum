<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_user_can_view_all_threads()
    {
        $thread = factory('App\Model\Thread')->create();

        $response = $this->get('/threads');
        $response->assertSee($thread->title);
    }

    public function test_a_user_can_read_a_single_thread()
    {
        $thread = factory('App\Model\Thread')->create();

        $response = $this->get('/threads/'.$thread->id);
        $response->assertSee($thread->title);
    }
}
