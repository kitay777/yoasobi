<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Inertia\Inertia;
use App\Models\User;
use App\Models\AdvisorProfile;
use App\Models\AdvisorSubscriptionPlan;
use App\Models\AppealTag;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Carbon\Carbon;



class AdvisorProfileController extends Controller
{
    public function index()
    {
        $profiles = \App\Models\AdvisorProfile::latest()->get();

        return Inertia::render('Advisor/List', [
            'profiles' => $profiles,
        ]);
    }

    public function verifyintro()
    {
        $profiles = \App\Models\AdvisorProfile::latest()->get();

        return Inertia::render('Advisor/VerifyIntro', [
            'profiles' => $profiles,
        ]);
    }
    public function verifypreview()
    {
        $profiles = \App\Models\AdvisorProfile::latest()->get();

        return Inertia::render('Advisor/VerifyPreview', [
            'profiles' => $profiles,
        ]);
    }

    public function mypage(Request $request)
    {
        //$user = $request->user();
        $user = Auth::user();
        $profile = $user->advisorProfile;

        $identityVerification = \App\Models\IdentityVerification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest('created_at')
            ->first();

        if (!$identityVerification) {
            // pendingがなければ最新（approved/rejected/inactiveも含めて最新）を取得
            $identityVerification = \App\Models\IdentityVerification::where('user_id', $user->id)
                ->latest('created_at')
                ->first();
        }
        return Inertia::render('Advisor/MyPage', [
            'advisor' => [
                'id' => $user->id,
                'profile_name' => $profile->name ?? $user->name,
                'age' => $profile->age ?? null,
                'bio' => $profile->bio ?? '',
                'profile_photo_path' => $user->profile_photo_path,
                'cover_photo_path' => $profile->cover_path ?? '',
                'tags' => json_decode($profile->tags ?? '[]'),
                'subscription_price' => $profile->subscription_price ?? 10000,
                'identity_status' => $identityVerification->status ?? null,
            ],
        ]);
    }

    public function castmypage()
    {
        $profiles = \App\Models\CastProfile::latest()->get();

        return Inertia::render('Cast/MyPage', [
            'user' => Auth::user(),
            'profiles' => $profiles,
        ]);
    }

    public function edit(Request $request)
    {
        $profile = $request->user()->advisorProfile()->with('appealTags')->first();

        return Inertia::render('Advisor/EditProfile', [
            'profile' => $profile,
            'tags' => $profile?->appealTags->pluck('name') ?? [],
        ]);
    }
    public function menu(Request $request)
    {
        $profile = $request->user()->advisorProfile()->with('appealTags')->first();

        return Inertia::render('Advisor/Menu', [
            'user' => Auth::user(),
            'profile' => $profile,
            'tags' => $profile?->appealTags->pluck('name') ?? [],
        ]);
    }

    public function edit2(Request $request)
    {
        $profile = $request->user()->advisorProfile()->with('appealTags')->first();

        return Inertia::render('Advisor/EditProfile2', [
            'profile' => $profile,
            'tags' => $profile?->appealTags->pluck('name') ?? [],
        ]);
    }


        public function update(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'age' => 'required|integer|min:18|max:99',
                'region' => 'required|string|max:50',
                'bio' => 'nullable|string|max:1000',
                'subscription_price' => 'nullable|integer|min:0',
                'talk_price' => 'nullable|integer|min:0',
                'avatar' => 'nullable|image|max:102400',
                'cover' => 'nullable|image|max:204800',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:20',
            ]);

                $user = $request->user();
                $profile = $user->advisorProfile()->firstOrNew([]);

                // ファイル保存処理
                if ($request->hasFile('avatar')) {
                    $avatarPath = $request->file('avatar')->store('avatars', 'public');
                    $profile->avatar_path = $avatarPath;
                }
                if ($request->hasFile('cover')) {
                    $coverPath = $request->file('cover')->store('covers', 'public');
                    $profile->cover_path = $coverPath;
                }

                // 他の値をセット
                $profile->fill($validated);
                $profile->save();

                // タグ処理（省略）

                return back()->with('success', 'プロフィールを更新しました。');
        }

        public function update2(Request $request)
        {
            $validated = $request->validate([

                'avatar' => 'nullable|image|max:102400',
                'cover' => 'nullable|image|max:204800',

            ]);

                $user = $request->user();
                $profile = $user->advisorProfile()->firstOrNew([]);

                // ファイル保存処理
                if ($request->hasFile('avatar')) {
                    $avatarPath = $request->file('avatar')->store('avatars', 'public');
                    $profile->avatar_path = $avatarPath;
                }
                if ($request->hasFile('cover')) {
                    $coverPath = $request->file('cover')->store('covers', 'public');
                    $profile->cover_path = $coverPath;
                }


                $profile->save();

                // タグ処理（省略）

                return redirect()->route('dashboard')->with('success', 'アバターとカバーを登録しました');
        }


    public function editIntroduction()
    {
        return Inertia::render('Advisor/RegisterIntroduction', [
            // 'introduction' => auth()->user()->profile->introduction,
        ]);
    }

    public function updateIntroduction(Request $request)
    {
        $validated = $request->validate([
            'introduction' => 'required|string|max:3000',
        ]);

$request->user()->advisorProfile()->updateOrCreate(
    ['user_id' => $request->user()->id], // ← 検索条件
    ['introduction' => $validated['introduction']]
);


        return redirect()->route('dashboard')->with('success', '自己紹介を更新しました。');
    }

    public function editAppealTags()
    {
        $profile = auth()->user()->advisorProfile;
        $tags = $profile?->appealTags()->pluck('name');

        return Inertia::render('Advisor/RegisterAppealTags', [
            'tags' => $tags,
        ]);
    }

    public function updateAppealTags(Request $request)
    {
        $validated = $request->validate([
            'tags' => 'required|array',
            'tags.*' => 'string|max:20',
        ]);

        $profile = $request->user()->advisorProfile;

        // タグを一括作成または取得
        $tagIds = collect($validated['tags'])->map(function ($name) {
            return \App\Models\AppealTag::firstOrCreate(['name' => $name])->id;
        });

        $profile->appealTags()->sync($tagIds);

        return redirect()->route('dashboard')->with('success', 'アピールタグを更新しました。');
    }
    public function subscriptionPlan()
    {
        return $this->hasOne(\App\Models\AdvisorSubscriptionPlan::class);
    }

    public function editSubscription()
    {
        $price = auth()->user()->advisorProfile->subscriptionPlan->price ?? null;

        return Inertia::render('Advisor/RegisterSubscription', [
            'price' => $price,
        ]);
    }

    public function updateSubscription(Request $request)
    {
        $validated = $request->validate([
            'price' => 'required|integer|min:100|max:100000',
        ]);

        $profile = $request->user()->advisorProfile;

        $profile->subscriptionPlan()->updateOrCreate([], [
            'price' => $validated['price'],
        ]);

        return redirect()->route('dashboard')->with('success', 'サブスク料金を設定しました。');
    }
    public function show($id)
    {
        $user = auth()->user();
        $advisor = User::with('advisorProfile')
            ->where('id', $id)
            ->where('role', 'advisor')
            ->firstOrFail();
        $isLiked = \App\Models\AdvisorLike::where('user_id', auth()->id())
            ->where('advisor_id', $advisor->id)
            ->exists();
        $subscriptionActive = Subscription::where('cast_id', $user->id)
            ->where('advisor_id', $advisor->id)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', Carbon::today())
            ->exists();
        $identityVerification = \App\Models\IdentityVerification::where('user_id', $advisor->id)
            ->where('status', 'pending')
            ->latest('created_at')
            ->first();
        if (!$identityVerification) {
            $identityVerification = \App\Models\IdentityVerification::where('user_id', $advisor->id)
                ->latest('created_at')
                ->first();
        }
        return Inertia::render('Advisor/ProfileShow', [
            'advisor' => [
                'id' => $advisor->id,
                'profile_name' => $advisor->advisorProfile->name ?? $advisor->name,
                'age' => $advisor->advisorProfile->age ?? null,
                'bio' => $advisor->advisorProfile->bio ?? '',
                'profile_photo_path' => $advisor->profile_photo_path,
                'profile_photo_url' => $advisor->profile_photo_url,
                'cover_photo_path' => $advisor->advisorProfile->cover_path ?? '',
                'tags' => json_decode($advisor->advisorProfile->tags ?? '[]'),
                'subscription_price' => $advisor->advisorProfile->subscription_price ?? 10000,
                'is_liked' => $isLiked,
                'identity_status' => $identityVerification->status ?? null,
            ],
            'subscription_active' => $subscriptionActive,
        ]);
    }
    public function editTalkPrice()
    {
        $profile = Auth::user()->advisorProfile;
        return inertia('Advisor/TalkPriceEdit', [
            'talk_price' => $profile->talk_price,
        ]);
    }

    public function updateTalkPrice(Request $request)
    {
        $validated = $request->validate([
            'talk_price' => 'required|integer|min:0|max:100000',
        ]);
        $profile = Auth::user()->advisorProfile;
        $profile->talk_price = $validated['talk_price'];
        $profile->save();

        // 完了後の遷移先（例: ダッシュボードなど）
        return redirect('/dashboard');
    }

    public function store(Request $request)
    {
        $request->validate([
            'birthdate' => 'required|date',
            'image' => 'required|image|max:4096',
        ]);

        $user = auth()->user();

        // 前の本人確認申請を「inactive」にする
        \App\Models\IdentityVerification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'inactive']);

        // 新規レコード追加
        $path = $request->file('image')->store('identity', 'public');

        \App\Models\IdentityVerification::create([
            'user_id'     => $user->id,
            'birthdate'   => $request->birthdate,
            'image_path'  => $path,
            'status'      => 'pending',
            'reviewed_by' => null,
            'reviewed_at' => null,
            'comment'     => null,
        ]);

        return redirect()->route('advisor.VerifyEnd');
    }
    public function end()
    {
        // 必要なら認証チェックや審査中データの取得も可
        // 何もなければ単純にビューを返すだけでOK

        return inertia('Advisor/VerifyEnd');
    }

}
