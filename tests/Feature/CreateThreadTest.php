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

        $thread = make('App\Model\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))->assertSee($thread->title)->assertSee($thread->body);
    }

    public function test_a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function test_a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function test_a_thread_requires_a_valid_channel()
    {
        factory('App\Model\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])  // channle_id 为 999，是一个不存在的 Channel
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Model\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
