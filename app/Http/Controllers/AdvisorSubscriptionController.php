<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvisorSubscriptionController extends Controller
{
    public function edit()
    {
        $profile = Auth::user()->advisorProfile;
        return inertia('Advisor/SubscriptionEdit', [
            'subscription_price' => $profile->subscription_price,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'subscription_price' => 'required|integer|min:0|max:100000',
        ]);

        $profile = Auth::user()->advisorProfile;
        $profile->subscription_price = $validated['subscription_price'];
        $profile->save();

        return redirect('/advisor/talk-price');
        
    }
}

