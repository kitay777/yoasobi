<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CastProfile;
use Inertia\Inertia;
use Auth;

class CastProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = CastProfile::firstOrCreate(['user_id' => $user->id]);

        return Inertia::render('Cast/ProfileEdit', [
            'profile' => $profile,
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $profileData = $request->validate([
            'name'            => 'nullable|string|max:50',
            'age'             => 'nullable|integer|min:16|max:99',
            'region'          => 'nullable|string|max:50',
            'experience'      => 'nullable|string|max:255',
            'support_request' => 'nullable|string|max:1000',
        ]);
        $avatarRule = ['avatar' => 'nullable|image|max:2048'];
        $request->validate($avatarRule);

        // 1. cast_profiles テーブル側の更新
        $profile = CastProfile::firstOrCreate(['user_id' => $user->id]);
        $profile->update($profileData);

        // 2. users テーブル側の画像だけ更新
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        return redirect()->route('cast.profile.edit');
    }


}

