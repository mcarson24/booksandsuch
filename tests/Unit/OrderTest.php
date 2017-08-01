<?php

namespace Tests\Unit;

use App\Book;
use App\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OrderTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function an_order_knows_what_book_it_sold()
    {
    	$book = factory(Book::class)->create(['title' => '1984']);
	    
	    $order = factory(Order::class)->create(['book_id' => $book->id]);

	    $this->assertEquals('1984', $order->book->title);
    }
}
