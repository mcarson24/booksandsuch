<?php

namespace Tests\Feature;

use App\Book;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewingBooksTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_user_can_view_a_published_book()
	{
		$this->disableExceptionHandling();

	    $book = factory(Book::class)->states('published')->create([
	    	'title' 		=> 'The Name of the Wind',
	    	'author'		=> 'Patrick Rothfuss',
	    	'release_date'	=> Carbon::parse('March 27, 2007'),
	    	'description'   => "Told in Kvothe's own voice, this is the tale of the magically gifted young man who grows to be the most notorious 					wizard his world has ever seen. 

								The intimate narrative of his childhood in a troupe of traveling players, his years spent as a near-feral orphan in crime-ridden city, his daringly brazen yet successful bid to enter a legendary school of magic, and his life as fugitive after the murder of a king form a gripping coming-of-age story unrivaled in recent literature. 

								A high-action story written with a poet's hand, The Name of the Wind is a masterpiece that will transport readers into the body and mind of a wizard.",
			'price'			=> 1200
    	]);

		$response = $this->get('books/1');

		$response->assertSee('The Name of the Wind')
				 ->assertSee('Patrick Rothfuss')
				 ->assertSee('March 27, 2007')
				 ->assertSee("Told in Kvothe's own voice,")
				 ->assertSee('$12.00');
	}

	/** @test */
	public function user_cannot_view_an_unpublished_book()
	{
	    $book = factory(Book::class)->states('unpublished')->create();

	    $response = $this->get("books/{$book->id}");

	    $response->assertStatus(404);
	}

	/** @test */
	public function can_view_all_of_a_users_books()
	{
	    $user = factory(User::class)->create();

	    $book = factory(Book::class)->make([
	    	'title'   => 'The Count of Monte Cristo',
	    	'user_id' => $user->id
    	])->toArray();

	    $response = $this->postJson('books', $book);
	    
	    $response->assertJson([
	    	'title' => 'The Count of Monte Cristo'
    	]);
	}
}
