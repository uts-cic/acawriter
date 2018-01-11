<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    //
    public function assignments() {
        return $this->hasMany('App\Assignment');
    }

    public function drafts() {
        return $this->hasMany('App\Draft');
    }

}
