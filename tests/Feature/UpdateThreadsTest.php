<?php

namespace Tests\Feature;

use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();
        $this->signIn();
    }

    public function test_unauthorized_users_may_not_update_threads()
    {
        $thread = create('Thread', ['user_id' => create('User')->id]);
        $this->patch($thread->path(), [])->assertStatus(403);
    }

    public function test_a_thread_requires_a_title_and_body_to_be_updated()
    {
        $thread = create('Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed.'
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed.'
        ])->assertSessionHasErrors('title');
    }

    public function test_a_thread_can_be_updated_by_its_creator()
    {
        $thread = create('Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'changed',
            'body'  => 'changed body'
        ]);

        tap($thread->fresh(), function ($thread) {
            $this->assertEquals('changed', $thread->title);
            $this->assertEquals('changed body', $thread->body);
        });
    }
}
