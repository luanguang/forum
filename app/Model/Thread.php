<?php

namespace App\Model;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadHasNewReply;
use App\Events\ThreadReceivedNewReply;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['creator', 'channel'];
    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions->where('user_id', '!=', $reply->user_id)->each->notify($reply);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        // event(new ThreadHasNewReply($this, $reply));

        // $this->subscriptions->where('user_id', '!=', $reply->user_id)->each->notify($reply);

        // $this->subscriptions->filter(function ($sub) use ($reply) {
        //     return $sub->user_id != $reply->user_id;
        // })->each->notify($reply);

        // $this->subscriptions->filter(function ($sub) use ($reply) {
        //     return $sub->user_id != $reply->user_id;
        // })->each(function ($sub) use ($reply) {
        //     $sub->user->notify(new ThreadWasUpdated($this, $reply));
        // });

        // foreach ($this->subscriptions as $subscription) {
        //     if ($subscription->user_id != $reply->user_id) {
        //         $subscription->user->notify(new ThreadWasUpdated($this, $reply));
        //     }
        // }

        return $reply;
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($user_id = null)
    {
        $this->subscriptions()->create([
            'user_id' => $user_id ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($user_id = null)
    {
        $this->subscriptions()
            ->where('user_id', $user_id ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }
}
