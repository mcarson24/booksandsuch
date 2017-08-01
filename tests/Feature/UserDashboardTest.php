<?php

namespace Tests\Feature;

use App\Book;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserDashboardTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
    	$this->disableExceptionHandling();
        $this->signIn();

        $response = $this->get('home');

        $response->assertSee(auth()->user()->name);
    }

    /** @test */
    public function a_users_dashboard_displays_their_uploaded_books()
    {
        $user = $this->signIn();

        $books = array_map(function($book) use ($user) {
        	return factory(Book::class)->create(['user_id' => $user->id]);
        }, range(1, 3));

        $response = $this->get('home');

        array_map(function($book) use ($response) {
        	$response->assertSee($book->title);
        }, $books);
    }

    /** @test */
    public function a_users_dashboard_displays_their_purchased_books()
    {
        $this->disableExceptionHandling();
        $user = $this->signIn();

        $book = factory(Book::class)->states('published')->create(['title' => '1984']);
        $order = $book->createOrder($user->id, 1200);
        
        $response = $this->get('home');

        $response->assertSee('1984');
    }
}
