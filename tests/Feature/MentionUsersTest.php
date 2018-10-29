<?php

namespace Tests\Feature;

use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    public function test_mentioned_users_in_a_reply_are_notified()
    {
        $john = create('User', ['name' => 'John']);

        $this->signIn($john);

        $jane = create('User', ['name' => 'jane']);

        $thread = create('Thread');

        $reply = make('Reply', ['body' => '@jane look at this. And also @Luke']);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());
        //只有第一次能成功因为jane这个名字不是唯一的
        $this->assertCount(1, $jane->notifications);
    }

    public function test_it_can_fetch_all_users_starting_with_the_given_characters()
    {
        create('User', ['name' => 'johndoe']);
        create('User', ['name' => 'johndoe2']);
        create('User', ['name' => 'janedoe']);

        $result = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $result->json());
    }
}
