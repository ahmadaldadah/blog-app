
<div class="mb-2 mt-2">

    @auth
        <form action="{{$route}}" method="POST">
            @csrf

            <div class="form-group">
                <textarea class="form-control" name="content"></textarea>
            </div>


            <button type="submit" class="btn btn-primary btn-block">{{ __('Add comment') }}</button>
        </form>
        @errors @enderrors
    @else
        <a href="{{ route('login') }}">{{ __('Sign-in') }}</a> {{ __('to post comments!') }}
    @endauth
    <hr/>
</div>
