<?php

namespace Tests\Unit;

use App\Book;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function a_user_knows_all_of_their_purchased_books()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();
        
        $order = $book->createOrder($user->id, 2000);
        
        $this->assertTrue($user->purchasedBooks()->first()->is($order));
    }

    /** @test */
    public function a_user_can_view_their_sold_books()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create(['name' => 'Book Shopper']);
        $book = factory(Book::class)->create([
        	'user_id' => $user->id, 
        	'title' => 'Sample Book'
    	]);
        $this->assertEquals('Sample Book', $user->uploadedBooks->first()->title);

        $book->createOrder($otherUser->id, $book->price);
        $this->assertEquals('Book Shopper', $user->soldBooks->first()->buyer->name);
    }
}
