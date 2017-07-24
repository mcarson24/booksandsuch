<?php

namespace Tests\Feature;

use App\Book;
use Tests\TestCase;
use App\Billing\PaymentGateway;
use App\Billing\FakePaymentGateway;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PurchaseBooksTest extends TestCase
{
	use DatabaseMigrations;

	private $paymentGateway;

	public function setUp()
	{
		parent::setUp();

		$this->paymentGateway = new FakePaymentGateway;
		$this->app->instance(PaymentGateway::class, $this->paymentGateway);
	}

    /** @test */
    public function user_can_purchase_books_to_a_published_book()
    {
    	$this->disableExceptionHandling();
    	$user = $this->signIn();
        $book = factory(Book::class)->states('published')->create([
        	'price' => 2000
    	]);

    	$this->postJson("books/{$book->id}/orders", [
			'email' 		=> auth()->user()->email,
			'payment_token'	=> $this->paymentGateway->getValidTestToken()
		]);

		$this->assertEquals(2000, $this->paymentGateway->totalCharges());
		$this->assertTrue($book->hasOrderFor(auth()->user()));
		$this->assertEquals(2000, $book->orders->where('email', $user->email)->first()->amount);
    }
}
