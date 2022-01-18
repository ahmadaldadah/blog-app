@extends('layouts.app')

@section('content')
    <h1>Contact</h1>
    <p>Hello how can i serve u</p>
    @can('home.secret')
        <a href="{{ route('secret') }}">Go To Special Link </a>
    @endcan
@endsection
