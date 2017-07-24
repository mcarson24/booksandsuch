<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function show(Book $book)
    {
    	return view('books.show', compact('book'));
    }

    public function store()
    {
    	$this->validate(request(), [
    		'title' 		=> 'required',
    		'author' 		=> 'required',
    		'description'	=> 'required|min:10',
    		'release_date'	=> 'required',
    		'price'			=> 'required|integer|min:1'
		]);

    	Book::create(request()->only(['title', 'author', 'description', 'price', 'release_date', 'published_at']));

    	return response()->json([], 201);
    }
}
