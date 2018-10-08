<?php

namespace Tests\Feature;

use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    public function unauthenticated_user_may_no_add_replies()
    {
        $this->expectedException('Illuminate\Auth\AuthenticationException');

        $this->post('thread/1/replies', []);
    }

    public function test_an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have a authenticated user
        $this->be($user = factory('App\Model\User')->create());
        // And an existing thread
        $thread = factory('App\Model\Thread')->create();
        // When the user adds a reply to the thread
        $reply = factory('App\Model\Reply')->make();
        $this->post($thread->path() . '/replies', $reply->toArray());
        // Then their reply should be visible on the page
        $this->get($thread->path())->assertSee($reply->body);
    }
}
