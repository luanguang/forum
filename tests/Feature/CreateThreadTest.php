<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    public function test_guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    public function test_an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = create('App\Model\Thread');

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())->assertSee($thread->title)->assertSee($thread->body);
    }
}
