<?php

namespace App\Model;

use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = [];
    protected $with = ['owner', 'favorites'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');//可不填第二个参数，填写只是便于理解
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }
}
