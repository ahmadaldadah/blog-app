@extends('layouts.app')
@section('title' ,'index post')
@section('content')
<div class="row">
    <div class="col-8">
    @forelse($posts as $post)
        @include('posts.partials.post')
        @empty
        <p>no posts yet</p>
    @endforelse
    </div>
    <div class="col-4">
        @include('posts.partials.activity')
    </div>
</div>

@endsection
