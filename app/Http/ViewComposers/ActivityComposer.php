<?php

namespace App\Http\ViewComposers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer{
     public function compose(View $view){
         $mostCommented = Cache::tags(['blog-post'])->remember('post-commented', now()->addSecond(10),function (){
             return Post::mostCommented()->take(5)->get();
         });
         $mostActive = Cache::remember('users-most-active', now()->addSecond(10),function (){
             return User::withMostPosts()->take(5)->get();
         });
         $mostActiveLastMonth = Cache::remember('users-most-active-last-month', now()->addSecond(10),function (){
             return User::withMostPostsLastMonth()->take(5)->get();
         });
        $view->with('mostCommented',$mostCommented);
        $view->with('mostActive',$mostActive);
        $view->with('mostActiveLastMonth',$mostActiveLastMonth);
     }
}
