<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function uploadBook(Request $request)
    {
        return $this->uploadedBooks()->create($request->all());
    }

    /**
     * A user can upload many books.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function uploadedBooks()
    {
        return $this->hasMany(Book::class, 'user_id');
    }

    /**
     * A user can have many orders.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * An alias for the orders relationship.
     * 
     * @return void
     */
    public function purchasedBooks()
    {   
        return $this->orders();
    }

    /**
     * A single user can sell many books.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function soldBooks()
    {
        return $this->hasManyThrough(Order::class, Book::class, 'user_id', 'book_id', 'id');
    }
}
