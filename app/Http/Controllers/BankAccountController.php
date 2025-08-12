<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\BankAccount;
use App\Models\User;

class BankAccountController extends Controller
{
    //
    public function show(Request $request)
    {
        $account = $request->user()->bankAccounts()->latest()->first();

        // すでに登録があれば完了画面へリダイレクト
        if ($account) {
            return redirect()->route('bankaccount.complete');
        }
        return Inertia::render('Advisor/BankAccountForm', [
            'account' => $account,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|max:20',
            'bank_name' => 'required|string|max:50',
            'bank_code' => 'nullable|string|max:10',
            'branch_name' => 'required|string|max:50',
            'branch_code' => 'nullable|string|max:10',
            'account_type' => 'required|in:普通,当座',
            'account_number' => 'required|digits_between:6,8',
            'account_holder_sei' => 'required|string|max:30',
            'account_holder_mei' => 'required|string|max:30',
            'invoice_number' => 'nullable|string|max:20',
            'consents' => 'required|array',
            'consents.*' => 'string',
        ]);

        $user = $request->user();

        $account = $user->bankAccounts()->create([
            ...$validated,
            'is_default' => false, // 必要に応じて
            'consents' => json_encode($validated['consents']),
        ]);
        return redirect()->route('bankaccount.complete'); // 完了画面など
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|max:20',
            'bank_name' => 'required|string|max:50',
            'branch_name' => 'required|string|max:50',
            'account_type' => 'required|string|max:20',
            'account_number' => 'required|digits_between:6,8',
            'account_holder_sei' => 'required|string|max:30',
            'account_holder_mei' => 'required|string|max:30',
            'invoice_number' => 'nullable|string|max:20',
        ]);

        $account = BankAccount::findOrFail($id);

        // 念のため本人以外のレコードは弾く
        if ($account->user_id !== $request->user()->id) {
            abort(403);
        }

        $account->update($validated);

        return redirect()->route('bankaccount.changed'); // 変更完了画面など
    }


    public function complete(Request $request)
    {
        $account = $request->user()->bankAccounts()->latest()->first();

        return Inertia::render('Advisor/BankAccountComplete', [
            'account' => $account,
        ]);
    }
    public function changed()
    {
        return Inertia::render('Advisor/BankAccountChanged');
    }

}
