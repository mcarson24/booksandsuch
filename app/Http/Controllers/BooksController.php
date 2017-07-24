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
}
