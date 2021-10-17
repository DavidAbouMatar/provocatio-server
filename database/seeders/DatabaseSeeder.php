<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $user = \App\Models\User::factory(10)->make();
        // \App\Models\User::truncate();
         \App\Models\User::factory(10)->create();
        Post::factory()->count(10)->create();
        Comment::factory()->count(10)->create();
    }
}
