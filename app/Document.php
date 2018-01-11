<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];

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
