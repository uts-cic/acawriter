<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Example extends Model
{

    public function feature()
    {
        return $this->belongsTo('App\Feature', 'feature_id', 'id');
    }
}
