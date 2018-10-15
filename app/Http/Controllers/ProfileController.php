<?php

namespace App\Http\Controllers;

use App\Model\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        return view('profile.show', [
            'profileUser' => $user,
            // 'threads'     => $user->threads()->paginate(10),
            'activities' => $this->getActivity($user),
        ]);
    }

    public function getActivity(User $user)
    {
        return $user->activities()->latest()->take(50)->with('subject')->get()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }
}
