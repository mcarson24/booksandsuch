<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddingBooksTest extends TestCase
{
	use DatabaseMigrations;

	private $response;

	private function assertHasValidationError($field)
	{
		$this->response->assertStatus(422);
        $this->assertArrayHasKey($field, $this->response->decodeResponseJson());
	}

    /** @test */
    public function a_user_can_add_an_unpublished_book()
    {
    	$this->disableExceptionHandling();

    	$book = factory(Book::class)->make();

        $response = $this->post('books', [
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

		$response->assertStatus(201);
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

    /** @test */
    public function a_book_must_have_description()
    {
        $book = factory(Book::class)->make(['description' => null])->toArray();

        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('description');
    }

    /** @test */
    public function a_book_must_have_a_release_date()
    {
        $book = factory(Book::class)->make(['release_date' => null])->toArray();

        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('release_date');
    }

    /** @test */
    public function a_book_must_have_a_price()
    {
        $book = factory(Book::class)->make(['price' => null])->toArray();

        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('price');
    }

    /** @test */
    public function a_books_price_must_be_an_integer()
    {
        $book = factory(Book::class)->create(['price' => '12.50'])->toArray();
        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('price');
    }
}
