<?php

namespace Tests\Feature;

use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    public function unauthenticated_user_may_no_add_replies()
    {
        $this->withExceptionHandling()
            ->post('thread/some_channel/1/replies', [])
            ->assertRedirect('/login');
    }

    public function test_an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have a authenticated user
        $this->signIn();
        // And an existing thread
        $thread = create('Thread');
        // When the user adds a reply to the thread
        $reply = make('Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    public function test_a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('Thread');
        $reply = make('Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    public function test_unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create('Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    public function test_unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create('Reply', ['user_id' => auth()->id()]);

        $updateReply = 'You have been changed,foo.';
        $this->patch("/replies/{$reply->id}", ['body' => $updateReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updateReply]);
    }

    public function test_replies_that_contain_spam_may_not_be_created()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = create('Thread');
        $reply = make('Reply', [
            'body' => 'something forbidden'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())->assertStatus(422);
    }

    public function test_users_may_only_reply_a_maximum_of_once_per_minute()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = create('Thread');
        $reply = make('Reply', [
            'body' => 'My simple reply.'
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertStatus(200);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertStatus(429);
    }
}
