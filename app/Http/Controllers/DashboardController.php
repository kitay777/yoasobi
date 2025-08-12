<?php

namespace App\Http\Controllers;

use App\Models\AdvisorProfile;
use App\Models\AdvisorLike;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // ✅ アカウント名未登録なら設定画面へ
        if (empty($user->account_name)) {
            return redirect()->route('account.setup');
        }

        // advisorの場合は登録内容をチェックし、すべてOKならmypageへ
        if ($user->role === 'advisor') {
            if (empty($user->advisorProfile?->introduction)) {
                return redirect()->route('advisor.introduction.edit');
            }
            if ($user->advisorProfile && $user->advisorProfile->appealTags()->count() === 0) {
                return redirect()->route('advisor.appeal-tags.edit');
            }
            if ($user->advisorProfile && !$user->advisorProfile->subscription_price) {
                return redirect()->route('advisor.subscription2.edit');
            }
            if ($user->advisorProfile && !$user->advisorProfile->talk_price) {
                return redirect()->route('advisor.talk_price.edit');
            }
            if ($user->advisorProfile && !$user->advisorProfile->avatar_path) {
                return redirect()->route('advisor.profile2.edit');
            }
            if ($user->advisorProfile && !$user->advisorProfile->cover_path) {
                return redirect()->route('advisor.profile2.edit');
            }
            // ここまで来たら全て登録済み → /advisor/mypage にリダイレクト
            return redirect()->route('advisor.mypage');
        }

        // advisor以外は今まで通りDashboard
        $profiles = AdvisorProfile::with('appealTags')->latest()->get();

        $likedAdvisorIds = AdvisorLike::where('user_id', $user->id)->pluck('advisor_id')->toArray();

        $profiles = $profiles->map(function ($profile) use ($likedAdvisorIds) {
            return [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                'name' => $profile->name,
                'age' => $profile->age,
                'cover_path' => $profile->cover_path,
                'bio' => $profile->bio,
                'appeal_tags' => $profile->appealTags,
                'is_liked' => in_array($profile->user_id, $likedAdvisorIds),
            ];
        });

        return Inertia::render('Dashboard', [
            'profiles' => $profiles,
            'auth' => [
                'user' => $user,
            ],
        ]);
    }

}
