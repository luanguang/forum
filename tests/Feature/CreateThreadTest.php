<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    public function test_guests_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException'); // 在此处抛出异常即代表测试通过

        $thread = make('App\Model\Thread');
        $this->post('/threads', $thread->toArray());
    }

    public function test_an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Model\Thread');

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())->assertSee($thread->title)->assertSee($thread->body);
    }

    public function test_guests_may_not_see_the_create_thread_page()
    {
        $this->withExceptionHandling()->get('/threads/create')->assertRedirect('/login');
    }
}
