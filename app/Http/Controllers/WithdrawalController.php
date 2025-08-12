<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserWithdrawal;

class WithdrawalController extends Controller
{
    //
    public function confirm(Request $request)
    {
        $request->validate(['message' => 'nullable|string|max:1000']);
        UserWithdrawal::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);
        $user = auth()->user();
        $user->is_withdrawn = true;
        $user->withdrawn_at = now();
        $user->save();

        // ログアウト
        auth()->logout();

        return redirect()->route('taikai.thanks');
    }

}
