<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;

class Favorite extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    public function favorited()
    {
        return $this->morphTo();
    }
}
