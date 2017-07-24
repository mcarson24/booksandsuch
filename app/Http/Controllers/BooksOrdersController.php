<?php

namespace App\Http\Controllers;

use App\Billing\PaymentGateway;
use App\Book;
use App\Order;
use Illuminate\Http\Request;

class BooksOrdersController extends Controller
{
	protected $paymentGateway;

	public function __construct(PaymentGateway $paymentGateway)
	{
		$this->paymentGateway = $paymentGateway;
	}

    public function store($id)
    {
    	$book = Book::published()->findOrFail($id);
    	
    	$this->paymentGateway->charge($book->price, request('payment_token'));

    	$order = $book->createOrder(auth()->id(), $this->paymentGateway->totalCharges());

    	return response()->json($order, 201);
    }
}
