@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-heading">Your Uploaded Books</div>
                                </div>
                                <div class="panel-body">
                                    <ul>
                                        @foreach($user->UploadedBooks as $book)
                                            <li><a href="{{ action('BooksController@show', $book) }}">{{ $book->title }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-heading">Your Purchased Books</div>
                                </div>
                                <div class="panel-body">
                                    <ul>
                                        @foreach($user->purchasedBooks as $order)
                                            <li><a href="{{ action('BooksController@show', $order->book) }}">{{ $order->book->title }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-heading">Your Uploaded Books</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
