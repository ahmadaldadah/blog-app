@extends('layouts.app')

@section('content')
    <h1>Secret page</h1>
    <p>hi hi hi</p>
    @can('home.secret')
        <p>Special</p>
    @endcan
@endsection
