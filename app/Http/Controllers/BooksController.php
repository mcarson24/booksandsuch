<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function show($id)
    {
    	$book = Book::whereNotNull('published_at')->findOrFail($id);

    	return view('books.show', compact('book'));
    }
}
