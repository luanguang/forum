<?php

namespace App\Filters;

use App\Model\User;

class ThreadsFilters extends Filters
{
    protected $filters = ['by', 'popularity', 'unanswered'];

    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    public function popularity()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'DESC');
    }

    public function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}
