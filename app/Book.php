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

    public function createOrder($email, $amount)
    {
        return $this->orders()->create([
            'email'     => $email,
            'amount'    => $amount
        ]);
    }

    public function orders()
    {
        return $this->HasMany(Order::class);
    }

    public function hasOrderFor(User $user)
    {
        return $this->orders()->where('email', $user->email)->count() > 0;
    }
}
