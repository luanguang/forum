<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Thread;
use App\Model\Reply;
use App\Inspections\Spam;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreatePostRequest;
use App\Model\User;
use App\Notifications\YouWereMentioned;

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

    public function store($channel_id, Thread $thread, CreatePostRequest $form)
    {
        $reply = $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
        ]);

        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matched);

        $names = $matched[1];

        foreach ($names as $name) {
            $user = User::whereName($name)->first();

            if ($user) {
                $user->notify(new YouWereMentioned($reply));
            }
        }

        return $reply->load('owner');

        // if (Gate::denies('create', Reply::class)) {
        //     return response('You are posting too frequently.Please take a break.:)', 422);
        // }

        // try {
        //     // $this->validateReply();
        //     // $this->authorize('create', Reply::class);
        //     $this->validate(request(), ['body' => 'required|spamfree']);

        // $reply = $thread->addReply([
        //     'body'    => request('body'),
        //     'user_id' => auth()->id(),
        //     ]);
        // } catch (\Exception $e) {
        //     return response('Sorry,your reply could not be saved at this time.', 422);
        // }

        // if (request()->expectsJson()) {
        //     return $reply->load('owner');
        // }

        // return back()->with('flash', 'Your reply has been left.');

        // return $reply = $thread->addReply([
        //     'body'    => request('body'),
        //     'user_id' => auth()->id(),
        // ])->load('owner');
    }

    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);
        try {
            $this->validate(request(), ['body' => 'required|spamfree']);

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

    // public function validateReply()
    // {
    //     $this->validate(request(), [
    //         'body' => 'required'
    //     ]);

    //     //化为实例 new Spam 一个意思
    //     resolve(Spam::class)->detect(request('body'));
    // }
}
