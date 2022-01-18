<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class CommentObserver
{
   public function creating(Comment $comment){
       if ($comment->commentable_type === Post::class){

           Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
           Cache::tags(['blog-post'])->forget('mostCommented');
       }
   }
}
