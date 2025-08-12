<?php

namespace App\Http\Controllers;


use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create()
    {
        return Inertia::render('Post/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->file('image')?->store('posts', 'public');

        Post::create([
            'user_id' => $user->id,
            'content' => $request->input('content'),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('dashboard')->with('message', '投稿しました');
    }
}
