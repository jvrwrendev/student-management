<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Show the home page with the latest posts fetched from the Post model.
     */
    public function home(): View
    {
        $posts = Post::latest()->get();

        return view('home', compact('posts'));
    }

    /**
     * Show the full text of a single post (route-model binding).
     */
    public function show(Post $post): View
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the about page.
     */
    public function about(): View
    {
        $team = [
            ['name' => 'Alex Morgan', 'role' => 'Lead Developer'],
            ['name' => 'Sam Rivera', 'role' => 'Frontend Engineer'],
            ['name' => 'Jordan Lee', 'role' => 'UX Designer'],
        ];

        return view('about', [
            'companyName' => config('app.name'),
            'team' => $team,
        ]);
    }

    /**
     * Show the contact page.
     */
    public function contact(): View
    {
        return view('contact', [
            'email' => 'hello@example.com',
            'phone' => '+1 (555) 000-1234',
            'address' => '123 Main Street, Springfield',
        ]);
    }
}
