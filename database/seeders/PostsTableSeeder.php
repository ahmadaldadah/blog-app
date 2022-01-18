<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $blogCount = (int)$this->command->ask('How many blog posts would you like?', 50);

            $users=User::all();
            Post::factory()->count($blogCount)->make()->each(function ($post) use ($users){
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
