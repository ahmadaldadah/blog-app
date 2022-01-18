<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment');
    }

    public function commentsOn(){
        return $this->morphMany('App\Models\Comment','commentable')->latest();
    }

    public function image(){
        return $this->morphOne('App\Models\Image','imageable');
    }

    public function scopeWithMostPosts(Builder $query){
        return $query->withCount('posts')->orderBy('posts_count','desc');
    }

    public function scopeWithMostPostsLastMonth(Builder $query){
        return $query->withCount(['posts'=>function(Builder $query){
            $query->whereBetween(static::CREATED_AT,[now()->subMonths(1),now()]);
        }])
            ->has('posts','>=',2)
            ->orderBy('posts_count','desc');
    }
    public function scopeThatHasCommentedOnPost(Builder $query, Post $post){
         return $query->whereHas('comments',function ($query) use ($post){
            return $query->where('commentable_id','=',$post->id)
                ->where('commentable_type','=',Post::class);
        });
    }
    public function scopeThatIsAnAdmin(Builder $query){
        return $query->where('is_admin',true);
    }
}
