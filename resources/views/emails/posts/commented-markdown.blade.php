@component('mail::message')
# Comment was posted on ur post

Hi {{ $comment->commentable->user->name }}

Someone has commented in your post
@component('mail::button', ['url' => route('posts.show',['post'=>$comment->commentable->id])])
View the post
@endcomponent

@component('mail::button', ['url' => route('users.show',['user'=>$comment->user->id])])
Visit {{  $comment->user->name }} profile
@endcomponent

@component('mail::panel')
    {{ $comment->content }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
