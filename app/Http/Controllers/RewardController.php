<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\RewardRequest;

use Illuminate\Support\Facades\Auth;


class RewardController extends Controller
{
    public function apiIndex()
    {
        $user = Auth::user();
        return Reward::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

}
