<?php

namespace Database\Seeders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Create admin user
        User::create([
            'user_name' => 'Administrador',
            'email' => 'jimmisitho450@gmail.com',
            'code' => 0,
            'password' => bcrypt('admin'),
            'role_id' => 1,
            'remember_token' => Str::random(10),
        ]);


        User::factory()
        ->count(10)->create();

        User::factory()
        ->count(50)
        ->state(function (array $attributes) {
            return ['state' => '1'];
        })->create();

        $users = User::all();
        $users->each(function ($user) {
            // Crear y asociar los archivos al post
            if($user->role_id == 2){
                $user->posts()->saveMany([
                    Post::factory()->make(),
                    Post::factory()->make()
                ]);
            }
        });


        $posts = Post::all();

        $posts->each(function ($post) {
            // Crear y asociar los archivos al post
            $post->files()->saveMany([
                File::factory()->type('cover_image')->make(),
                File::factory()->type('file')->make(),
            ]);
        });

    }
}
