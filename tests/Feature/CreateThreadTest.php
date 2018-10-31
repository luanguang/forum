<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\Activity;

class CreateThreadTest extends TestCase
{
    public function test_guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('threads/create')
            ->assertRedirect(route('login'));

        $this->post(route('threads'))
            ->assertRedirect(route('login'));
    }

    public function test_a_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('Thread');

        $response = $this->post(route('threads'), $thread->toArray());

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

        $this->publishThread(['channel_id' => 9999])  // channle_id 为 999，是一个不存在的 Channel
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

        //assertDatabaseMissing不存在
        // $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        // $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        // $this->assertDatabaseMissing('activities', [
        //     'subject_id'   => $thread->id,
        //     'subject_type' => get_class($thread),
        // ]);

        // $this->assertDatabaseMissing('activities', [
        //     'subject_id'   => $reply->id,
        //     'subject_type' => get_class($reply)
        // ]);

        $this->assertEquals(1, Activity::count());

        // $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }

    public function test_guests_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('Thread');

        $response = $this->delete($thread->path());

        $response->assertRedirect(route('login'));
    }

    public function test_unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('Thread');

        $this->delete($thread->path())->assertRedirect(route('login'));

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('Thread', $overrides);

        return $this->post(route('threads'), $thread->toArray());
    }

    public function test_new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory('App\Model\User')->states('unconfirmed')->create();

        $this->signIn($user);

        $thread = make('Thread');

        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must first confirm your email address.');
    }
}
