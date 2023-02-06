@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div style="display:flex; flex-wrap: wrap; gap:20px;">
            @foreach($books as $book) <book-card :book="{{$book}}"></book-card> @endforeach
        </div>
    </div>
</div>
@endsection

<!-- https://8b26bbc90eb349f6b7f036723bad9c1c.us-central1.gcp.cloud.es.io -->