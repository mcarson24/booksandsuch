<?php

namespace App;

use App\Order;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];
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

    public function shouldBeHidden()
    {
        return $this->published_at == null && $this->user_id != auth()->id();
    }

    public function createOrder($user_id, $amount)
    {
        return $this->orders()->create([
            'user_id'     => $user_id,
            'amount'      => $amount
        ]);
    }

    public function orders()
    {
        return $this->HasMany(Order::class);
    }

    public function hasOrderFor(User $user)
    {
        return $this->orders()->where('user_id', $user->id)->count() > 0;
    }
}
