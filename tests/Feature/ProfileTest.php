<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function test_a_user_has_a_profile()
    {
        $this->signIn();

        $this->get('/profile/' . auth()->user()->name)
            ->assertSee(auth()->user()->name);
    }

    public function test_profiles_display_all_threads_created_by_the_associated_user()
    {
        $this->signIn();

        $thread = create('Thread', ['user_id' => auth()->id()]);

        $this->get('/profile/' . auth()->user()->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
