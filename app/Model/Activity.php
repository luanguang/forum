<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function subject()
    {
        return $this->morphTo();
    }

    public static function feed($user, $take = 50)
    {
        // return $user->activities()->latest()->with('subject')->take(50)->get()->groupBy(function ($activity) {
        //     return $activity->created_at->format('Y-m-d');
        // });
        return static::where('user_id', $user->id)->latest()->take($take)->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }
}
