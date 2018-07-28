<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable;

    protected $guarded = [];
    protected $with = ['favorites', 'owner'];

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
