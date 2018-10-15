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

        $this->assertEquals(13, Activity::count()); //数量随着测试的改变而改变
    }
}
