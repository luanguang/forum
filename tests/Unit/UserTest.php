<?php

namespace Tests\Unit;

use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_a_user_can_fetch_their_most_recent_reply()
    {
        $user = create('User');

        $reply = create('Reply', ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    public function test_a_user_can_determine_their_avatar_path()
    {
        $user = create('User');

        $this->assertEquals('avatars/default.jpg', $user->avatar());

        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals('avatars/me.jpg', $user->avatar());
    }
}
