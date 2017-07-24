<?php

namespace Tests\Unit;

use App\Book;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BookTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function it_can_correctly_format_the_release_date()
    {
        $book = factory(Book::class)->create(['release_date' => Carbon::parse('June 1, 2016')]);

        $this->assertEquals('June 1, 2016', $book->formatted_release_date);
    }

    /** @test */
    public function it_can_correctly_format_the_price()
    {
        $book = factory(Book::class)->create(['price' => '1200']);

        $this->assertEquals('12.00', $book->formatted_price);
    }
}
