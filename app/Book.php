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
            'buyer_id'     => $user_id,
            'amount'      => $amount
        ]);
    }

    public function orders()
    {
        return $this->hasOne(Order::class);
    }

    public function hasOrderFor(User $user)
    {
        return $this->orders()->where('buyer_id', $user->id)->count() > 0;
    }

    public function uploaded_by()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
