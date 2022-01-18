<?php

namespace App\Http\Controllers;

use App\Events\PostPosted;
use App\Http\Requests\StorePost;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create','store','edit','update','destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//
        return view('posts.index',
            [  'posts'=>Post::latestWithRelations()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePost $request)
    {
        $validated =$request->validated();
        $validated['user_id']= $request->user()->id;
        $post = Post::create($validated);

        if ($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('thumbnail');
            $post->image()->save(
                Image::make(['path'=>$path])
            );
        }

        event(new PostPosted($post));

        $request->session()->flash('status','Post was created');
        return redirect()-> route('posts.show',['post' => $post -> id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        return view('posts.show',['post'=>Post::with(['comments'=>function($query){
//            return $query->latest();
//        }])->findOrFail($id)]);

        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}",60,function () use ($id){
           return Post::with('comments','tags','user','comments.user')
               ->findOrFail($id);
        });

        $sessionId = session()->getId();
        $counterKey ="blog-post-{$id}-counter";
        $userKey="blog-post-{$id}-users";

        $users = Cache::tags(['blog-post'])->get($userKey,[]);
        $usersUpdate=[];
        $difference = 0;
        $now = now();

        foreach ($users as $session =>$lastVisit){
            if ($now->diffInMinutes($lastVisit)>=1){
                $difference--;
            }else{
                $usersUpdate[$session]=$lastVisit;
            }
         }

        if (
            !array_key_exists($sessionId,$users)
            || $now->diffInMinutes($users[$sessionId])>=1
        ){
            $difference++;
        }
        $usersUpdate[$sessionId]=$now;
        Cache::tags(['blog-post'])->forever($userKey,$usersUpdate);

        if (!Cache::tags(['blog-post'])->has($counterKey)){
            Cache::tags(['blog-post'])->forever($counterKey,1);
        }else{
            Cache::tags(['blog-post'])->increment($counterKey,$difference);
        }

        $counter = Cache::tags(['blog-post'])->get($counterKey);

        return view('posts.show',[
            'post'=>$blogPost,
            'counter'=>$counter
            ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
//        if(Gate::denies('update-post',$post)){
//            abort(403,"You can't edit this post");
//        }
        $this->authorize($post);

        return view('posts.edit',['post'=>$post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize($post);

        $validated = $request->validated();

        $post->fill($validated);

        if ($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('thumbnail');

            if ($post->image){
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            }else{
                $post->image()->save(
                    Image::make(['path'=>$path])
                );
            }

        }

        $post->save();
        $request->session()->flash('status','Blog post was updated');
        return redirect()->route('posts.show',['post'=>$post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
//        if(Gate::denies('delete-post',$post)){
//            abort(403,"You can't delete this post");
//        }
        $this->authorize($post);


        $post->delete();
        session()->flash('status','Post was delete');
        return redirect()->route('posts.index');
    }
}
