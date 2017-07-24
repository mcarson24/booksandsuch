<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $dates = ['release_date'];

    public function scopePublished($query)
    {
    	return $query->whereNotNull('published_at');
    }

    public function getFormattedReleaseDateAttribute()
    {
    	return $this->release_date->format('F j, Y');
    }

    public function getFormattedPriceAttribute()
    {
    	return number_format($this->price / 100, 2);
    }
}
