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

        $thread = make('Thread');

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

    /* assertDatabaseHas 和 assertDatabaseMissing方法不存在 */
    public function test_authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('Thread', ['user_id' => auth()->id()]);
        $reply = create('Reply', ['thread_id' => $thread->id]);
        // $this->assertDatabaseHas('threads', ['id' => $thread->id]);
        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        // $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }

    public function test_guests_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('Thread');

        $response = $this->delete($thread->path());

        $response->assertRedirect('/login');
    }

    public function test_unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
