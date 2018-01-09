<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    //
    public function user() {
        return $this->belongsTo(User::class);
    }


    public function document() {
        return $this->belongsTo(Assignment::class);
    }

    public function feature() {
        return $this->belongsTo(Feature::class);
    }
}
