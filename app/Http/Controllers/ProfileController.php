<?php

namespace App\Http\Controllers;

use Cache;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Activity;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        return view('profile.show', [
            'profileUser' => $user,
            'activities'  => Activity::feed($user),
        ]);
    }

    public function signIn(User $user)
    {
        if (Cache::has($user->id)) {
            $data = Cache::get($user->id);
            if ($data['time'] == Carbon::today()) {
                return response()->json(['message' => 'failed']);
            }
            Cache::put([$user->id => ['time' => Carbon::today(), 'count' => $data['count'] + 1]], Carbon::tomorrow()->addDay(1));
            return response()->json(['message' => 'success', 'count' => $data['count'] + 1]);
        }
        Cache::put([$user->id => ['time' => Carbon::today(), 'count' => 1]], Carbon::tomorrow()->addDay(1));
        return response()->json(['message' => 'success', 'count' => 1]);
    }
}
