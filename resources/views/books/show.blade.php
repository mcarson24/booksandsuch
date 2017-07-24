<h1>{{ $book->title }}</h1>
<h2>by: {{ $book->author }}</h2>
<h3>{{ $book->formatted_release_date }}</h3>
<hr>
<span>${{ $book->formatted_price }}</span>
<p>{!! $book->description !!}</p>