<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddingBooksTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function a_user_can_add_an_unpublished_book()
    {
    	$this->disableExceptionHandling();

    	$book = factory(Book::class)->make();

        $this->post('books', [
        	'title' 		=> $book->title,
        	'author'		=> $book->author,
        	'release_date'	=> $book->release_date,
        	'description'	=> $book->description,
        	'price'			=> $book->price
    	]);

    	$this->assertDatabaseHas('books', [
    		'title'			=> $book->title,
    		'author' 		=> $book->author,
    		'published_at'	=> null
		]);
    }

    /** @test */
    public function a_book_must_have_a_title()
    {
        $book = factory(Book::class)->make(['title' => null])->toArray();

        $response = $this->postJson('books', $book);

        $response->assertStatus(422);
        $this->assertArrayHasKey('title', $response->decodeResponseJson());
    }

    /** @test */
    public function a_book_must_have_an_author()
    {
        $book = factory(Book::class)->make(['author' => null])->toArray();

        $response = $this->postJson('books', $book);

        $response->assertStatus(422);
        $this->assertArrayHasKey('author', $response->decodeResponseJson());
    }
}
