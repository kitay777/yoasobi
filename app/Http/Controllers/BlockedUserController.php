<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\BlockedUser;


class BlockedUserController extends Controller
{
    // BlockedUserController.php

    public function index(Request $request)
    {
        // 自分がブロックしているユーザー一覧
        $blocked = \App\Models\BlockedUser::with('blockedUser')
            ->where('user_id', $request->user()->id)
            ->get();
        return Inertia::render('Advisor/BlockedList', [
            'blocked' => $blocked,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        // idは blocked_usersテーブルの主キー
        $block = \App\Models\BlockedUser::findOrFail($id);
        if ($block->user_id !== $request->user()->id) abort(403);
        $block->delete();
        return redirect()->route('blocked-users.index');
    }

}
