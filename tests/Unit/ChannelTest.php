<?php

namespace Tests\Unit;

use Tests\TestCase;

class ChannelTest extends TestCase
{
    public function test_a_channel_consists_of_threads()
    {
        $channel = create('Channel');
        $thread = create('Thread', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
