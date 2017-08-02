<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $guarded = [];

	public function book()
	{
		return $this->belongsTo(Book::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'buyer_id');
	}

	public function buyer()
	{
		return $this->user();
	}
}
