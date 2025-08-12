<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function editAccountName()
    {
        return Inertia::render('Account/Setup', [
            'user' => Auth::user(),
        ]);
    }

    public function storeAccountName(Request $request)
    {
        $request->validate([
            'account_name' => ['required', 'string', 'min:3', 'max:8', 'unique:users,account_name'],
        ]);

        $user = Auth::user();
        $user->account_name = $request->input('account_name');
        $user->save();

        if ($user->role === 'cast') {
            return redirect()->route('cast.support');
        } else {
            return redirect()->route('dashboard'); 
        }
    }

}
