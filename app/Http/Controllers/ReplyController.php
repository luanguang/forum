<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Thread;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channel_id, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
        ]);

        return redirect($thread->path())->with('flash', 'Your reply has been left.');
    }
}
