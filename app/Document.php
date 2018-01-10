<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    //
    public function assignment() {
        return $this->belongsTo('App\Assignment','assignment_id','id');
    }

    public function drafts() {
        return $this->hasMany('App\Draft');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}
