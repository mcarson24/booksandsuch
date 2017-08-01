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
}
