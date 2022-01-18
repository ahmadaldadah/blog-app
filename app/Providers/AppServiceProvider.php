<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use App\Models\Comment;
use App\Models\Post;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::aliasComponent('components.badge','badge');
        Blade::aliasComponent('components.updated','update');
        Blade::aliasComponent('components.card','card');
        Blade::aliasComponent('components.tags','tags');
        Blade::aliasComponent('components.errors','errors');
        Blade::aliasComponent('components.comment-form','commentForm');
        Blade::aliasComponent('components.comment-list','commentList');

        view()->composer(['posts.index','posts.show'],ActivityComposer::class);

        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);



        Schema::defaultStringLength(191);

    }
}
