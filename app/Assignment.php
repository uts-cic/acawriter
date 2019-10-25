<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];

    //
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function feature()
    {
        return $this->belongsTo('App\Feature', 'feature_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }
}
