<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Subscription;



class SubscriptionController extends Controller
{
    use AuthorizesRequests;
    // サブスク一覧（キャストとしての自分のサブスクのみ）
    public function index(Request $request)
    {
        $subscriptions = Subscription::with('advisor.advisorProfile')
            ->where('cast_id', $request->user()->id)
            ->where('status', 'active')
            ->get();

        return Inertia::render('Subscription/Index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    // サブスク詳細
    public function show(Subscription $subscription)
    {
        // 認可：本人以外NG
        $this->authorize('view', $subscription);

        return Inertia::render('Subscription/Show', [
            'subscription' => $subscription->load('advisor'),
        ]);
    }

    // サブスク解除
    public function cancel(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        $subscription->status = 'cancelled';
        $subscription->end_date = now();
        $subscription->cancel_reason = $request->input('cancel_reason');
        $subscription->save();

        return redirect()->route('subscriptions.index')->with('message', 'サブスクを解除しました');
    }
    // SubscriptionController@store


public function store(Request $request)
{
    $user = $request->user();
    $advisorId = $request->input('advisor_id');
    $price = $request->input('price'); 

    // 既存の有効サブスクを弾く or 上書きしたい場合は追加でバリデーション
    $already = Subscription::where('cast_id', $user->id)
        ->where('advisor_id', $advisorId)
        ->where('status', 'active')
        ->whereNull('end_date')
        ->exists();
    if ($already) {
        return response()->json(['message' => 'すでに登録済みです'], 409);
    }

    $start = Carbon::now();
    $end = $start->copy()->addMonth();

    Subscription::create([
        'cast_id'    => $user->id,
        'advisor_id' => $advisorId,
        'price'      => $price, // 金額も送信する場合
        'start_date' => $start->toDateString(),
        'end_date'   => $end->toDateString(),
        'status'     => 'active',
    ]);

    return redirect()->back()->with('message', 'サブスク登録が完了しました');
}

}