<?php

namespace Tests\Feature;

use App\Book;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
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
    public function unauthenticated_users_cannot_upload_a_book()
    {
        $this->disableExceptionHandling();

        $book = factory(Book::class)->make();

        try {
            $response = $this->post('books', $book->toArray());
        } catch (AuthenticationException $e) {
            return;
        }
        $this->fail('Guests should not be able to create books.');
    }

    /** @test */
    public function an_authenticated_user_can_add_an_unpublished_book()
    {
        $this->signIn();
        $this->disableExceptionHandling();
    	$book = factory(Book::class)->make();
        $response = $this->post('books', $book->toArray());

    	$this->assertDatabaseHas('books', [
    		'title'			=> $book->title,
    		'author' 		=> $book->author,
    		'published_at'	=> null
		]);

		$response->assertStatus(201);
    }

    /** @test */
    public function an_authenticated_user_can_add_an_published_book()
    {
        $this->signIn();

    	$book = factory(Book::class)->states('published')->make();
    	
        $response = $this->post('books', $book->toArray());

    	$this->assertDatabaseHas('books', [
    		'title'			=> $book->title,
    		'author' 		=> $book->author,
    		'published_at'	=> $book->published_at
		]);

		$response->assertStatus(201);

		$response = $this->get('books/1');
		$response->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_can_delete_their_own_uploaded_books()
    {
        $this->disableExceptionHandling();
        $this->signIn();
        $book = factory(Book::class)->states('published')->create();
        $this->delete("books/{$book->id}");

        $this->assertDatabaseMissing('books', [
            'title'     => $book->title,
            'user_id'   => $book->user_id
        ]);
    }

    /** @test */
    public function a_book_must_have_a_title()
    {
        $this->signIn();
        $book = factory(Book::class)->make(['title' => null])->toArray();

        $response = $this->postJson('books', $book);

        $response->assertStatus(422);
        $this->assertArrayHasKey('title', $response->decodeResponseJson());
    }

    /** @test */
    public function a_book_must_have_an_author()
    {
        $this->signIn();
        $book = factory(Book::class)->make(['author' => null])->toArray();

        $response = $this->postJson('books', $book);

        $response->assertStatus(422);
        $this->assertArrayHasKey('author', $response->decodeResponseJson());
    }

    /** @test */
    public function a_book_must_have_description()
    {
        $this->signIn();
        $book = factory(Book::class)->make(['description' => null])->toArray();

        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('description');
    }

    /** @test */
    public function a_book_must_have_a_release_date()
    {
        $this->signIn();
        $book = factory(Book::class)->make(['release_date' => null])->toArray();

        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('release_date');
    }

    /** @test */
    public function a_book_must_have_a_price()
    {
        $this->signIn();
        $book = factory(Book::class)->make(['price' => null])->toArray();

        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('price');
    }

    /** @test */
    public function a_books_price_must_be_an_integer()
    {
        $this->signIn();
        $book = factory(Book::class)->create(['price' => '12.50'])->toArray();
        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('price');
    }

    /** @test */
    public function a_books_price_must_be_greater_than_zero()
    {
        $this->signIn();
        $book = factory(Book::class)->create(['price' => '-2400'])->toArray();
        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('price');
    }

    /** @test */
    public function a_books_description_should_be_at_least_ten_characters()
    {
        $this->signIn();
        $book = factory(Book::class)->create(['description' => 'Short De'])->toArray();
        $this->response = $this->postJson('books', $book);

        $this->assertHasValidationError('description');
    }
}
