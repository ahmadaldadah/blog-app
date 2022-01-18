<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <script src="{{mix('js/app.js')}}" defer></script>
    <title>Laravel app @yield('title')</title>
</head>
<body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 bg-white border-bottom shadow-sm mb-3">
        <h5 class="my-0 mr-md-auto font-weight-normal">Laravel Blog</h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="p-2 text-dark" href="{{route('home')}}" >{{__('Home')}}</a>
            <a class="p-2 text-dark" href="{{route('contact')}}" >{{__('Contact')}}</a>
            <a class="p-2 text-dark" href="{{route('posts.index')}}">{{__('Blog Posts')}}</a>
            <a class="p-2 text-dark" href="{{route('posts.create')}}">{{__('Add')}}</a>
            @guest
                @if(Route::has('register'))
                    <a class="p-2 text-dark" href="{{route('register')}}">{{__('Register')}}</a>
                @endif
                <a class="p-2 text-dark" href="{{route('login')}}">{{__('Login')}}</a>

            @else
                <a class="p-2 text-dark" href="{{route('logout')}}"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{__('Logout')}} ({{ Auth::user()->name }})</a>
                <form action="{{route('logout')}}" id="logout-form" method="POST" style="display: none">
                    @csrf
                </form>
            @endguest
        </nav>
    </div>
    <div class="container">
        @if(session('status'))
            <div class="alert alert-success">
                {{session('status')}}
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>
