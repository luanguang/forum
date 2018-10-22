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
        try {
            $this->validateReply();

            $reply = $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            return response('Sorry,your reply could not be saved at this time.', 422);
        }

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);
        try {
            $this->validateReply();

            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response('Sorry,your reply could not be saved at this time.', 422);
        }
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

    public function validateReply()
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        //化为实例 new Spam 一个意思
        resolve(Spam::class)->detect(request('body'));
    }
}
