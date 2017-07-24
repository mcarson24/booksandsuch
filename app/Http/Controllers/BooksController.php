<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BooksController extends Controller
{
	public function __construct()
	{
        $this->middleware('auth')->only('store');
	}

    public function show($id)
    {
        $book = Book::findOrFail($id);
        
        if ($book->shouldBeHidden())
        {   
            throw new ModelNotFoundException;
        }

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

    	$book = auth()->user()->uploadBook(request());

    	return response()->json($book, 201);
    }

    public function destroy(Book $book)
    {
        $book->delete();
    }
}
