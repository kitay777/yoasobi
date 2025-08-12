<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ChatMessage;

class ChatOverviewController extends Controller
{
    public function index()
    {
        $currentUserId = Auth::id();

        // ✅ 1. 仮マッチ一覧（例：全ユーザー）
        $recentMatches = User::where('id', '!=', $currentUserId)
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'photo' => $user->profile_photo_url,
                'label' => '仮マッチ表示中',
            ]);

        // ✅ 2. やりとりした相手の ID を取得
        $partnerIds = ChatMessage::where('sender_id', $currentUserId)
            ->orWhere('receiver_id', $currentUserId)
            ->pluck('sender_id', 'receiver_id')
            ->flatten()
            ->reject(fn ($id) => $id == $currentUserId)
            ->unique()
            ->values();

        // ✅ 3. 各相手との最終メッセージ・未読件数をまとめて取得
        $unreadMessages = $partnerIds->map(function ($partnerId) use ($currentUserId) {
            $partner = User::find($partnerId);

            $lastMessage = ChatMessage::where(function ($q) use ($currentUserId, $partnerId) {
                    $q->where('sender_id', $currentUserId)->where('receiver_id', $partnerId);
                })
                ->orWhere(function ($q) use ($currentUserId, $partnerId) {
                    $q->where('sender_id', $partnerId)->where('receiver_id', $currentUserId);
                })
                ->latest()
                ->first();

            $unreadCount = ChatMessage::where('sender_id', $partnerId)
                ->where('receiver_id', $currentUserId)
                ->where('read', false)
                ->count();

            return [
                'id' => $partner->id,
                'name' => $partner->name,
                'photo' => $partner->profile_photo_url,
                'lastMessage' => Str::limit(optional($lastMessage)->text ?? '', 30),
                'lastTime' => optional($lastMessage)->created_at?->format('H:i'),
                'unread' => $unreadCount,
            ];
        })->sortByDesc('lastTime')->values();

        return response()->json([
            'recentMatches' => $recentMatches,
            'unreadMessages' => $unreadMessages,
        ]);
    }
}
