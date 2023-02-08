@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <shop
                :isbns="{{$isbns}}"
                :authors="{{$authors}}"
                :genres="{{$genres}}"
            ></shop>
        </div>
    </div>
</div>
@endsection