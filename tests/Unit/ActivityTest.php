<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;
use App\Model\Activity;

class ActivityTest extends TestCase
{
    public function test_it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create('Thread');

        $this->assertDatabaseHas('activities', [
            'id'           => 1,
            // 'user_id'      => auth()->id(),
            // 'subject_id'   => $thread->id,
            'subject_type' => 'App\Model\Thread',
            'type'         => 'created_thread',
            // 'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            // 'updated_at'   => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // $activity = Activity::first(); //确保数据库只有一条数据才能成功

        // $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function test_it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create('Reply');

        $this->assertEquals(14, Activity::count()); //数量随着测试的改变而改变
    }

    public function test_it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        // Given we have a thread
        create('Thread', ['user_id' => auth()->id()], 2);

        // And another thread from a week ago
        auth()->user()->activities()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        // When we fetch their feed
        $feed = Activity::feed(auth()->user());

        // Then,it should be returned in the proper format.
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
