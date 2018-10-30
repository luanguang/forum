<?php

namespace App\Http\Controllers;

use App\Model\Thread;
use Illuminate\Http\Request;
use App\Model\Channel;
use App\Filters\ThreadsFilters;
use App\Inspections\Spam;
use App\Trending;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, ThreadsFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads'  => $threads,
            'trending' => $trending->get()
        ]);
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request, Spam $spam)
    {
        $this->validate($request, [
            'title'      => 'required|spamfree',
            'body'       => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
        ]);

        $spam->detect($request['body']);

        $thread = Thread::create([
            'title'      => request('title'),
            'body'       => request('body'),
            'user_id'    => auth()->id(),
            'channel_id' => request('channel_id'),
        ]);

        return redirect($thread->path())->with('flash', 'Your thread has been published!');
    }

    public function show($channel_id, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->increment('visits');

        return view('threads.show', compact('thread'));
    }

    public function edit(Thread $thread)
    {
        //
    }

    public function update(Request $request, Thread $thread)
    {
        //
    }

    public function destroy($channel, Thread $thread)
    {
        // $thread->replies()->delete();
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    protected function getThreads(Channel $channel, ThreadsFilters $filters)
    {
        $threads = Thread::with('channel')->latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->paginate(20);

        return $threads;
    }
}
