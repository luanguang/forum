<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;

class ThreadTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->thread = create('Thread');
    }

    public function test_a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\Model\User', $this->thread->creator);
    }

    public function test_a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body'    => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_belongs_to_a_channel()
    {
        $thread = create('Thread');

        $this->assertInstanceOf('App\Model\Channel', $thread->channel);
    }

    public function test_a_thread_can_make_a_string_path()
    {
        $thread = create('Thread');

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $thread = create('Thread');

        // $this->signIn();

        $thread->subscribe($user_id = 1);

        // $thread->subscriptions()->where('user_id', auth()->id())->get();
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $user_id)->count());
    }

    public function test_a_thread_can_be_unsubscribed_from()
    {
        $thread = create('Thread');

        $thread->subscribe($user_id = 1);

        $thread->unsubscribe($user_id);

        $this->assertCount(0, $thread->subscriptions);
    }

    public function test_it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create('Thread');

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    public function test_a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()->thread->subscribe()->addReply([
            'body'    => 'Foobar',
            'user_id' => 999
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    public function test_a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('Thread');

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    public function test_a_thread_records_each_visit()
    {
        $thread = make('Thread', ['id' => 1]);

        $thread->visits()->reset();
        $this->assertSame(0, $thread->visits()->count());

        $thread->visits()->record();
        $this->assertEquals(1, $thread->visits()->count());

        // $thread->recordVisit();
        // $this->assertEquals(2, $thread->visits());
    }
}
