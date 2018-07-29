<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = [];
    protected $with = ['favorites', 'owner'];

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
