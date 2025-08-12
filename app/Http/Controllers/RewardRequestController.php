<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RewardRequest;


class RewardRequestController extends Controller
{
    //
   public function apiStore(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1000',
            'bank_name' => 'required|string|max:255',
            'bank_account' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        $request = RewardRequest::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'note' => $request->note,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return response()->json(['message' => '申請を受け付けました。', 'data' => $request]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1000',
            'bank_name' => 'required|string|max:255',
            'bank_account' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        \App\Models\RewardRequest::create([
            'user_id' => $request->user()->id,
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'note' => $request->note,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return response()->json(['message' => '申請完了'], 201);
    }


}
