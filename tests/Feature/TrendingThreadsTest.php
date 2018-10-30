<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Trending;

class TrendingThreadsTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }

    public function test_it_increments_a_thread_score_each_time_it_is_read()
    {
        //测试前运行 php artisan cache:clear
        $this->assertEmpty($this->trending->get());

        $thread = create('Thread');

        $this->call('GET', $thread->path());

        $this->assertCount(1, $trending = $this->trending->get());

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
