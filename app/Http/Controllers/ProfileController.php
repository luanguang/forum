<?php

namespace App\Http\Controllers;

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
}
