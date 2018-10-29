<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $search = request('name');

        return User::where('name', 'LIKE', "$search%")
            ->take(5)
            ->pluck('name');
    }
}
