<?php

namespace App\Http\Controllers;

use App\Model\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        return view('profile.show', [
            'profileUser' => $user,
            'threads' => $user->threads()->paginate(10),
        ]);
    }
}
