<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    public function test_a_reply_has_an_owner()
    {
        $reply = create('Reply');

        $this->assertInstanceOf('App\Model\User', $reply->owner);
    }

    public function test_it_knows_if_it_was_just_published()
    {
        $reply = create('Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    public function test_it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create('Reply', [
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }
}
