<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\BlockedUser;
use App\Models\ChatMessage;







class ChatController extends Controller
{
    /**
     * チャットルームの表示
     */
    public function show($targetUserId)
    {
        $authId = Auth::id();

        $userOneId = min($authId, $targetUserId);
        $userTwoId = max($authId, $targetUserId);

        // 🔽 ルーム自動作成に変更（404 回避）
        $room = ChatRoom::firstOrCreate([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);

        // 既読処理（自分以外のメッセージに対して）
        foreach ($room->messages as $message) {
            if ($message->sender_id !== $authId && is_null($message->read_at)) {
                $message->markAsRead();
            }
        }

        return inertia('ChatRoom/ChatShow', [
            'room' => $room->load(['messages.sender']),
            'partnerUser' => $room->otherUser($authId),
        ]);
    }


    /**
     * メッセージ送信
     */
    public function store(Request $request)
    {
        $request->validate([
            'target_user_id' => ['required', 'exists:users,id'],
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $authId = Auth::id();
        $targetId = $request->input('target_user_id');

        $userOneId = min($authId, $targetId);
        $userTwoId = max($authId, $targetId);

        // ルーム取得または作成
        $room = ChatRoom::firstOrCreate([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);

        // メッセージ作成
        $message = $room->messages()->create([
            'sender_id' => $authId,
            'content' => $request->input('content'),
        ]);

        broadcast(new MessageSent($message->load('sender')))->toOthers();

        return response()->json(['status' => 'sent']);
    }

public function list()
{
    $userId = Auth::id();
    $now = now();

    // 有効サブスク（自分と有効な契約のある相手ID一覧）
    $subs = Subscription::where(function($q) use ($userId) {
            $q->where('cast_id', $userId)
              ->orWhere('advisor_id', $userId);
        })
        ->where('status', 'active')
        ->where(function($q) use ($now) {
            $q->whereNull('end_date')->orWhere('end_date', '>', $now);
        })
        ->where('start_date', '<=', $now)
        ->get();

    $partnerIds = $subs->map(function($sub) use ($userId) {
        return $sub->cast_id == $userId ? $sub->advisor_id : $sub->cast_id;
    })->unique()->values();

    $chatList = [];

    foreach ($partnerIds as $partnerId) {
        $sub = $subs->first(function($s) use ($partnerId, $userId) {
            return ($s->cast_id == $userId && $s->advisor_id == $partnerId)
                || ($s->advisor_id == $userId && $s->cast_id == $partnerId);
        });
        $start = $sub->start_date;
        $end = $sub->end_date ?? $now->copy()->addYears(10);

        // 最新メッセージ
        $lastMsg = \App\Models\ChatMessage::where(function($q) use ($userId, $partnerId) {
                $q->where(function($qq) use ($userId, $partnerId) {
                    $qq->where('sender_id', $userId)->where('receiver_id', $partnerId);
                })->orWhere(function($qq) use ($userId, $partnerId) {
                    $qq->where('sender_id', $partnerId)->where('receiver_id', $userId);
                });
            })
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('id')
            ->first();

        $partner = \App\Models\User::find($partnerId);
        $unreadCount = $lastMsg
            ? \App\Models\ChatMessage::where('sender_id', $partnerId)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->whereBetween('created_at', [$start, $end])
                ->count()
            : 0;

        $chatList[] = [
            'partner' => [
                'id' => $partner->id,
                'name' => $partner->name,
                'profile_photo_url' => $partner->profile_photo_url,
            ],
            'partner_id'   => $partner->id,
            'last_message' => $lastMsg ? $lastMsg->text : '',
            'last_time'    => $lastMsg ? $lastMsg->created_at->format('H:i') : '',
            'unread_count' => $unreadCount,
        ];
    }

    return Inertia::render('Chat/ChatList', [
        'chats' => $chatList,
    ]);
}

}
