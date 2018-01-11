<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Document;
use App\Feature;

class Draft extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];
    //
    public function user() {
        return $this->belongsTo(User::class);
    }


    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function feature() {
        return $this->belongsTo(Feature::class);
    }
}
