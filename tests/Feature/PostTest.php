<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoBlogPostsInDatabase()
    {
        $response = $this->get('/posts');
        $response->assertSeeText('no posts yet');
    }
    public function testSee1PostWhenThereIs1WithNoComments(){
        $post = $this->createDummyPost();


        $response =$this->get('/posts');

        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet');

        $this->assertDatabaseHas('posts',[
            'title'=>'New title'
        ]);
    }
    public function testSee1BlogPostWithComments(){
        $user = $this->user();
        $post = $this->createDummyPost();
        Comment::factory(5)->create([
            'commentable_id'=>$post->id,
            'commentable_type'=>'App\Models\Post',
            'user_id'=>$user->id
        ]);
        $response =$this->get('/posts');
    }

    public function testStoreValid(){

        $params = [
            'title'=>'valid title',
            'content'=>'At least 10 characters'
        ];
        $this->actingAs($this->user())
            ->post('/posts',$params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'),'the post was created');
    }

    public function testStoreFail(){
        $params = [
            'title'=>'a',
            'content'=>'a'
        ];

        $this->actingAs($this->user())->post('/posts',$params)->assertStatus(302)->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0],'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0],'The content must be at least 10 characters.');

    }
    public function testUpdateValid(){
        $user = $this->user();
        $post = $this->createDummyPost();

        $this->assertDatabaseHas('posts',$post->toArray());

        $params = [
            'title'=>'A new named title',
            'content'=>'Content was changed'
        ];

        $this->actingAs($this->user())->put("/posts/{$post->id}",$params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'),'Blog post was updated');
        $this->assertDatabaseMissing('posts',$post->toArray());
        $this->assertDatabaseHas('posts',[
            'title'=>'A new named title'
        ]);

    }
    public function testDelete(){
        $post = $this->createDummyPost();

        $this->assertDatabaseHas('posts',$post->toArray());

        $this->actingAs($this->user())->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'),'Post was delete');
//        $this->assertDatabaseMissing('posts',$post->toArray());
        $this->assertSoftDeleted('posts',$post->toArray());
    }

    private function createDummyPost(): Post{
        $post = new Post();
        $post -> title = 'New title';
        $post -> content = 'Content of the post';
        $post->save();
        return $post;
    }
}
