<?php

namespace App;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany('App\Favorite', 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if ( ! $this->favorites()->where($attributes)->exists()) {
            $this->favorites()->create($attributes);
        }
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count(); //注意，where不是同一个方法
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}