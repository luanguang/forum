<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function test_a_user_has_a_profile()
    {
        $user = create('User');

        $this->get('/profile/' . $user->name)
            ->assertSee($user->name);
    }

    public function test_profiles_display_all_threads_created_by_the_associated_user()
    {
        $user = create('User');

        $thread = create('Thread', ['user_id' => $user->id]);

        $this->get('/profile/' . $user->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
