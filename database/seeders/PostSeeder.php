<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::create([
            'title' => 'Welcome to Laravel MVC',
            'slug' => 'welcome-to-laravel-mvc',
            'content' => 'This demo application walks you through Laravel\'s MVC architecture. Every page you visit is served by a route, handled by a controller, and rendered through a Blade view. Requests that need data reach an Eloquent model first, then pass the results along to the view.',
        ]);

        Post::create([
            'title' => 'Models: Talking to the Database',
            'slug' => 'models-talking-to-the-database',
            'content' => 'Eloquent models sit at the heart of Laravel\'s data layer. They map PHP classes to database tables and provide expressive query builders. The Home page uses the Post model to fetch these seeded records from SQLite.',
        ]);

        Post::create([
            'title' => 'Controllers: The Request Handlers',
            'slug' => 'controllers-the-request-handlers',
            'content' => 'Controllers keep your routing file clean. Business logic lives in controller methods such as PageController@home, which gather the context a view needs and return a rendered Blade template.',
        ]);

        Post::create([
            'title' => 'Blade Views: The Presentation Layer',
            'slug' => 'blade-views-the-presentation-layer',
            'content' => 'Blade is Laravel\'s templating engine. Views live in resources/views and reuse a shared layout via Blade component layout syntax. Data is escaped with double curly braces to keep output safe.',
        ]);

        Post::create([
            'title' => 'Routing: One URL, One Action',
            'slug' => 'routing-one-url-one-action',
            'content' => 'Every request begins with a route defined in routes/web.php. Each route maps a URL to a controller method, and named routes let your views generate links with the route() helper.',
        ]);
    }
}
