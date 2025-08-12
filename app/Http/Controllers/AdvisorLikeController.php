<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdvisorLike;
use App\Models\User;

class AdvisorLikeController extends Controller
{
    /**
     * いいねを登録（既にあれば無視）
     */
    public function store($id)
    {
        $user = auth()->user();

        $alreadyLiked = AdvisorLike::where('user_id', $user->id)
            ->where('advisor_id', $id)
            ->exists();

        if (!$alreadyLiked) {
            AdvisorLike::create([
                'user_id' => $user->id,
                'advisor_id' => $id,
            ]);
        }

        return response()->json(['status' => 'liked']);
    }

    /**
     * いいねを解除
     */
    public function destroy($id)
    {
        $user = auth()->user();

        AdvisorLike::where('user_id', $user->id)
            ->where('advisor_id', $id)
            ->delete();

        return response()->json(['status' => 'unliked']);
    }

    /**
     * 自分がいいねした一覧を取得
     */
public function index(Request $request)
{
    $user = $request->user();

if ($user->role === 'cast') {
    $likes = AdvisorLike::with('advisor.advisorProfile')
        ->where('user_id', $user->id)
        ->get()
        ->map(function ($like) {
            $advisor = $like->advisor;

            return [
                'id' => $like->id,
                'advisor' => [
                    'id' => $advisor->id ?? null,
                    'name' => $advisor->name ?? '(不明)',
                    'profile_name' => $advisor->advisorProfile->name ?? '(未設定)',
                    'profile_photo_path' => $advisor->profile_photo_path,
                    'profile_photo_url' => $advisor->profile_photo_url,
                ],
                'last_message' => null,
                'unread_count' => 0,
            ];
        });
} else {
    $likes = AdvisorLike::with('user.advisorProfile')
        ->where('advisor_id', $user->id)
        ->get()
        ->map(function ($like) {
            $cast = $like->user;

            return [
                'id' => $like->id,
                'user' => [
                    'id' => $cast->id ?? null,
                    'name' => $cast->name ?? '(不明)',
                    'profile_name' => $cast->advisorProfile->name ?? '(未設定)',
                    'profile_photo_path' => $cast->profile_photo_path,
                    'profile_photo_url' => $cast->profile_photo_url,
                ],
                'last_message' => null,
                'unread_count' => 0,
            ];
        });
}


    return inertia('Likes/Index', [
        'likes' => $likes,
        'role' => $user->role,
    ]);
}





}
