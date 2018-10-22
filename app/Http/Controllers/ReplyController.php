<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Thread;
use App\Model\Reply;
use App\Inspections\Spam;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channel_id, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channel_id, Thread $thread, Spam $spam)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $spam->detect(request('body'));

        $reply = $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response((['status' => 'Reply deleted']));
        }

        return back();
    }
}
