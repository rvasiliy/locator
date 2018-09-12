<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCoordinate extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
