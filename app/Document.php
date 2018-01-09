<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    //
    public function assignments() {
        return $this->belongsTo('App\Assignment');
    }

    public function drafts() {
        return $this->hasMany('App\Draft');
    }
}
