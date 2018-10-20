<?php

namespace App\Http\Controllers;

use App\Model\Thread;

class ThreadSubscriptionController extends Controller
{
    public function store($channel_id, Thread $thread)
    {
        $thread->subscribe();
    }

    public function destroy($channel_id, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
