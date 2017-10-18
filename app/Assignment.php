<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Assignment extends Model
{
    //
    public function user() {
        $this->belongsTo(User::class);
    }


}
