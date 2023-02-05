@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <create-edit-book 
                :book="{{collect()}}"
                :authors="{{$authors}}"
                :genres="{{$genres}}"
                :publishers="{{$publishers}}"
            ></create-edit-book>
        </div>
    </div>
</div>
@endsection
