<?php

// app/Http/Controllers/PointController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\Checkout\Session;

use App\Models\PointTransaction;

class PointController extends Controller
{
    public function index()
    {
        return Inertia::render('Points/Index');
    }

    public function checkout(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $points = $request->input('points');
        $price = $points * 1; // 1ポイント = 100円と仮定

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => $price,
                    'product_data' => ['name' => "{$points} ポイント"],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('points.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('points.index'),
            'metadata' => [
                'user_id' => Auth::id(),
                'points' => $points,
            ],
        ]);

        return response()->json(['url' => $session->url]);
    }

// PointController.php の success()


    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($sessionId);

        // 既に処理済みならスキップ
        $alreadyExists = PointTransaction::where('stripe_session_id', $session->id)->exists();
        if ($alreadyExists) {
            return Inertia::render('Points/Success', [
                'points' => null, // または「すでに加算済みです」など表示用
            ]);
        }

        $user = \App\Models\User::find($session->metadata->user_id);
        $points = (int) $session->metadata->points;
        $amount = $session->amount_total;

        // ポイント加算
        $user->points += $points;
        $user->save();

        // 履歴を保存（これが2重防止にもなる）
        PointTransaction::create([
            'user_id' => $user->id,
            'points' => $points,
            'amount' => $amount,
            'stripe_session_id' => $session->id,
            'type' => 'purchase',
        ]);

        return Inertia::render('Points/Success', [
            'points' => $points,
        ]);
    }

    public function history()
    {
        $transactions = Auth::user()->pointTransactions()->latest()->paginate(10);

        return Inertia::render('Points/History', [
            'transactions' => $transactions
        ]);
    }


}
