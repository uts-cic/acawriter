<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    //
    public function user() {
        $this->belongsTo(User::class);
    }


    public function assignment() {
        $this->belongsTo(Assignment::class);
    }

    public function feature() {
        $this->belongsTo(Feature::class);
    }
}
