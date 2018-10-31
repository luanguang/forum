<?php

namespace App\Http\Controllers;

use App\Model\Thread;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}
