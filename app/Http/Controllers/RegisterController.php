<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::min(6)->max(16)->mixedCase()->numbers()],
            'birthday' => ['required', 'date'],
            'region' => ['required', 'string'],
            'experience' => ['required', 'string'],
        ]);

       $role = $request->input('role', 'cast');

            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'birthday' => $validated['birthday'],
                'region' => $validated['region'],
                'experience' => $validated['experience'],
                'role' => $role,
                'share_location' => true,
                'location_updated_at' => now(),
                // ★ ここが重要：POINT は (lng, lat) の順
                'location' => DB::raw('ST_SRID(POINT(0,0), 4326)'),
            ]);
 

        event(new Registered($user)); 
        auth()->login($user);

        // 登録後のリダイレクト先をroleで分岐
        if ($role === 'advisor') {
            return redirect()->route('verification.notice');
        }

        return redirect()->route('verification.notice'); // 通常のキャスト処理
    }
}
