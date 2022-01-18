<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if ($this->command->confirm('Do u want to refresh the database?',true)){
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }

        Cache::tags(['blog-post'])->flush();

        $this->call([
            UsersTableSeeder::class,
            PostsTableSeeder::class,
            CommentsTableSeeder::class,
            TagsTableSeeder::class,
            PostTagTableSeeder::class
            ]);


    }
}
