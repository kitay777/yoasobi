<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Media;
use Illuminate\Support\Facades\Auth; 
use App\Models\AdvisorProfile;

class MediaPostController extends Controller
{
    //
    public function create()
    {
        return Inertia::render('Post/MediaCreate');
    }
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpeg,png,jpg,mp4,mov,avi|max:20480',
        ]);

        $path = $request->file('file')->store('media', 'public');
        $type = $request->file('file')->getMimeType();

        Media::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'media_type' => str_starts_with($type, 'video') ? 'video' : 'image',
            'media_path' => $path,
        ]);

        return redirect()->route('dashboard')->with('success', 'メディアを投稿しました');
    }

}
