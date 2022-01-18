<style>
    body{
        font-family:Arial, Helvetica, sens-serif;
    }

</style>

<p> Hi {{ $comment->commentable->user->name }}</p>

<p>
    Someone has commented in your post
    <a href="{{route('posts.show',['post'=>$comment->commentable->id])}}">{{ $comment->commentable->title }}</a>
</p>

<hr/>

<p>
    <img src="{{ $comment->user->image->url() }}" />
    <a href="{{route('users.show',['user'=>$comment->user->id])}}">
        {{ $comment->user->name }}
    </a> said:
</p>

<p>
    "{{$comment->content}}"
</p>
