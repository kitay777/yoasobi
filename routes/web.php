<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\AdvisorProfileController;

use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CastSupportController;

use App\Http\Controllers\ChatController;

use App\Http\Controllers\ChatOverviewController;
use App\Models\ChatMessage;
use App\Models\User;
use App\Events\MessageSent;
use App\Http\Controllers\CastProfileController;
use App\Http\Controllers\AdvisorLikeController;
use App\Http\Controllers\CastLikeController;
use App\Http\Controllers\CastEntryController;
use App\Http\Controllers\AdvisorSubscriptionController;
use Illuminate\Support\Facades\Validator;
use App\Models\AdvisorProfile;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MediaPostController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BlockedUserController;
// routes/web.php

use App\Http\Controllers\PointController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RewardRequestController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DevSeedController;

Route::middleware(['web','auth'])->group(function () {
    // ローカル環境だけ許可
    Route::post('/dev/seed-nearby', [DevSeedController::class, 'seedNearby'])
        ->name('dev.seed-nearby');
});

Route::middleware(['web','auth'])
    ->prefix('api')
    ->group(function () {
        Route::post('/me/location', [LocationController::class, 'update']); // 現在地保存
        Route::get('/nearby',       [LocationController::class, 'nearby']); // 近隣検索
        Route::get('/me/location',  [LocationController::class, 'me']);     // 自分の保存位置 取得（フォールバック用）
    });


Route::middleware('auth:sanctum')->get('/nearby', [LocationController::class, 'nearby']);

// routes/web.php
//Route::post('/api/me/location', [\App\Http\Controllers\LocationController::class, 'update'])
//    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/rewards', fn () => Inertia::render('Rewards/Index'));
    Route::get('/api/rewards', [RewardController::class, 'apiIndex']);

    Route::get('/rewards/request', fn () => Inertia::render('Rewards/Request'));

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/points', [PointController::class, 'index'])->name('points.index');
    Route::post('/points/checkout', [PointController::class, 'checkout'])->name('points.checkout');
    Route::get('/points/success', [PointController::class, 'success'])->name('points.success');
    Route::get('/points/history', [PointController::class, 'history'])->name('points.history');
});


// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/media/create', [MediaPostController::class, 'create'])->name('media.create');
    Route::post('/media', [MediaPostController::class, 'store'])->name('media.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
});
Route::get('/api/advisors/{id}/posts', function ($id) {
    return \App\Models\Post::where('user_id', $id)
        ->orderByDesc('created_at')
        ->paginate(3); // ← 3件ずつ返す
});

Route::get('/taikai/thanks', fn() => Inertia::render('Help/TaikaiThanks'))->name('taikai.thanks');
Route::get('/riyoukiyaku', fn() => Inertia::render('Help/Rioukiyaku'))->name('taikai.riyoukiyaku');
Route::get('/privacy', fn() => Inertia::render('Help/Privacy'))->name('taikai.privacy');
Route::get('/tokutei', fn() => Inertia::render('Help/Tokutei'))->name('taikai.tokutei');
Route::get('/tokuyaku', fn() => Inertia::render('Help/Tokuyaku'))->name('taikai.tokuyaku');
Route::get('/credit', fn() => Inertia::render('Cast/Credit'))->name('cast.credit');
Route::get('/tuchi', fn() => Inertia::render('Cast/Tuchi'))->name('cast.tuchi');
Route::get('/unyo', fn() => Inertia::render('Cast/Unyo'))->name('cast.Unyo');
Route::get('/toiawase', fn() => Inertia::render('Cast/Toiawase'))->name('cast.toiawase');

Route::middleware(['auth'])->group(function () {
    // サブスク一覧（キャスト本人のサブスク登録情報）
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    // サブスク詳細
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    // サブスク解除（オプション）
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
});


// 退会確認（アンケート）ページ表示
Route::middleware(['auth', 'verified'])->group(function () {
    // 退会理由アンケート入力ページ
    Route::get('/taikai/confirm', function () {
        return Inertia::render('Help/TaikaiConfirm');
    })->name('taikai.confirm');

    // アンケート送信・退会リクエスト
    Route::post('/taikai/confirm', [WithdrawalController::class, 'confirm'])->name('taikai.confirm.submit');
});
Route::get('/login/line', function () {
    return Socialite::driver('line')->redirect();
})->name('login.line');
Route::get('/login/line/callback', function () {
    $user = Socialite::driver('line')->user();

    $email = $user->getEmail() ?? '';
    if (!$email) {
        $email = 'line_' . $user->getId() . '@example.com';
    }

    $appUser = \App\Models\User::where('line_id', $user->getId())
        ->orWhere('email', $email)
        ->first();

    if (!$appUser) {
        $appUser = \App\Models\User::create([
            'line_id' => $user->getId(),
            'name' => $user->getName() ?? $user->getNickname() ?? '',
            'email' => $email,
            'password' => bcrypt(\Str::random(32)),
            'email_verified_at' => now(), // ←ココ超重要！
        ]);
    } else {
        if (!$appUser->line_id) {
            $appUser->line_id = $user->getId();
        }
        // 既存ユーザーにもemail_verified_atを必ずセット
        if (!$appUser->email_verified_at) {
            $appUser->email_verified_at = now();
        }
        $appUser->save();
    }

    Auth::login($appUser, true);

    return redirect('/dashboard');
});




Route::middleware(['auth'])->group(function () {
    Route::get('/cast/profile/edit', [CastProfileController::class, 'edit'])->name('cast.profile.edit');
    Route::post('/cast/profile/update', [CastProfileController::class, 'update'])->name('cast.profile.update');
});
Route::middleware('auth:sanctum')->post('/api/block-user', function (Request $request) {
    $user = $request->user();

    $validator = Validator::make($request->all(), [
        'friend_id' => 'required|integer|exists:users,id|not_in:'.$user->id,
    ]);
    if ($validator->fails()) {
        return response()->json(['status' => 'invalid', 'errors' => $validator->errors()], 422);
    }

    $friendId = $request->input('friend_id');

    if (\App\Models\BlockedUser::where('user_id', $user->id)->where('blocked_user_id', $friendId)->exists()) {
        return response()->json(['status' => 'already_blocked']);
    }
    \App\Models\BlockedUser::create([
        'user_id' => $user->id,
        'blocked_user_id' => $friendId,
    ]);
    return response()->json(['status' => 'ok']);
});


Route::get('/api/advisors/{id}/media', function ($id) {
    $items = \App\Models\Media::where('user_id', $id)
        ->orderByDesc('created_at')
        ->paginate(3);

    // ここでmedia_urlキーを追加
    $items->getCollection()->transform(function ($item) {
        $item->media_url = asset('storage/' . $item->media_path);
        return $item;
    });

    return $items;
});

Route::middleware(['auth', 'can:admin'])->group(function () {
    //Route::post('/identity-verification/{id}/approve', [\App\Http\Controllers\IdentityVerificationController::class, 'approve'])->name('identity-verification.approve');
    //Route::post('/identity-verification/{id}/reject', [\App\Http\Controllers\IdentityVerificationController::class, 'reject'])->name('identity-verification.reject');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/advisor/verifyintro', [AdvisorProfileController::class, 'verifyintro'])->name('advisor.VerifyIntro');
    Route::get('/advisor/verifypreview', [AdvisorProfileController::class, 'verifypreview'])->name('advisor.VerifyPreview');
    Route::post('/advisor/identity', [AdvisorProfileController::class, 'store'])->name('advisor.store');
    Route::get('/advisor/verifyend', [AdvisorProfileController::class, 'end'])->name('advisor.VerifyEnd');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/advisor/mypage', [AdvisorProfileController::class, 'mypage'])->name('advisor.mypage');
    Route::get('/cast/mypage', [AdvisorProfileController::class, 'castmypage'])->name('cast.mypage');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/advisor/menu', [AdvisorProfileController::class, 'menu'])->name('advisor.menu');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/advisor/talk-price', [AdvisorProfileController::class, 'editTalkPrice'])->name('advisor.talk_price.edit');
    Route::post('/advisor/talk-price', [AdvisorProfileController::class, 'updateTalkPrice'])->name('advisor.talk_price.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/advisor/subscription/', [AdvisorSubscriptionController::class, 'edit'])->name('advisor.subscription2.edit');
    Route::post('/advisor/subscription', [AdvisorSubscriptionController::class, 'update'])->name('advisor.subscription.update');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/advisor/{id}', [AdvisorProfileController::class, 'show'])->name('advisor.profile.show');
    
});
Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');

Route::middleware(['auth'])->group(function () {
    Route::post('/advisor/{id}/like', [AdvisorLikeController::class, 'store'])->name('advisor.like');
    Route::delete('/advisor/{id}/like', [AdvisorLikeController::class, 'destroy'])->name('advisor.unlike');
    Route::get('/likes', [AdvisorLikeController::class, 'index'])->name('advisor.likes');
});

Route::middleware('auth')->get('/bank-account', [BankAccountController::class, 'show'])->name('bankaccount.show');
Route::middleware('auth')->post('/bank-account', [BankAccountController::class, 'store'])->name('bankaccount.store');
Route::middleware('auth')->get('/bank-account/complete', [BankAccountController::class, 'complete'])->name('bankaccount.complete');
Route::middleware('auth')->get('/bank-account/changed', [BankAccountController::class, 'changed'])->name('bankaccount.changed');
Route::middleware('auth')->patch('/bank-account/{id}', [BankAccountController::class, 'update'])->name('bankaccount.update');




/*
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/advisor/register/subscription', [AdvisorProfileController::class, 'editSubscription'])->name('advisor.subscription.edit');
    Route::post('/advisor/register/subscription', [AdvisorProfileController::class, 'updateSubscription'])->name('advisor.subscription.update');
});
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/advisor/register/appeal-tags', [AdvisorProfileController::class, 'editAppealTags'])->name('advisor.appeal-tags.edit');
    Route::post('/advisor/register/appeal-tags', [AdvisorProfileController::class, 'updateAppealTags'])->name('advisor.appeal-tags.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/advisor/register/introduction', [AdvisorProfileController::class, 'editIntroduction'])->name('advisor.introduction.edit');
    Route::post('/advisor/register/introduction', [AdvisorProfileController::class, 'updateIntroduction'])->name('advisor.introduction.update');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // ✅ account_name が未設定なら /account/setup に強制遷移
    Route::get('/start', function () {
        $user = Auth::user();

        if (empty($user->account_name)) {
            return redirect()->route('account.setup');
        }

        // 通常のスタートページ（例：ダッシュボードへ）
        return redirect()->route('dashboard');
    })->name('start');

    // 本編
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cast/support', [CastSupportController::class, 'create'])->name('cast.support');
    Route::post('/cast/support', [CastSupportController::class, 'store'])->name('cast.support.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/account/setup', [UserController::class, 'editAccountName'])->name('account.setup');
    Route::post('/account/setup', [UserController::class, 'storeAccountName'])->name('account.setup.store');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/advisors', [AdvisorProfileController::class, 'index'])->name('advisors.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/advisor/profile/edit', [AdvisorProfileController::class, 'edit'])->name('advisor.profile.edit');
    Route::post('/advisor/profile', [AdvisorProfileController::class, 'update'])->name('advisor.profile.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/advisor/profile2/edit', [AdvisorProfileController::class, 'edit2'])->name('advisor.profile2.edit');
    Route::post('/advisor/profile2', [AdvisorProfileController::class, 'update2'])->name('advisor.profile2.update');
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request, $id, $hash) {
    if (! URL::hasValidSignature($request)) {
        abort(403, 'Invalid signature');
    }

    Auth::loginUsingId($id);
    $request->fulfill(); // ← now works!
    return redirect('/verified');
})->middleware('auth')->name('verification.verify');



Route::middleware('auth')->get('/chat/list', [ChatController::class, 'list'])->name('chat.list');


Route::get('/email/verify', fn () => Inertia::render('Auth/EmailVerificationWait'))
    ->middleware(['auth'])
    ->name('verification.notice');


Route::get('/verified', fn () => Inertia::render('Auth/EmailVerified'))
    ->middleware(['auth', 'verified']) // ここもログインが必要
    ->name('verified.success');

Route::middleware('auth')->get('/blocked-users', [BlockedUserController::class, 'index'])->name('blocked-users.index');
Route::middleware('auth')->delete('/blocked-users/{id}', [BlockedUserController::class, 'destroy'])->name('blocked-users.destroy');


Route::get('/first-step', fn () => Inertia::render('FirstStep'));
Route::get('/cast-entry', fn () => Inertia::render('CastEntry'));
Route::get('/cast-intro', fn () => Inertia::render('CastIntro'));
Route::get('/advisor-entry', fn () => Inertia::render('AdvisorEntry'));
Route::get('/advisor-intro', fn () => Inertia::render('AdvisorIntro'));
Route::get('/register-select', fn () => Inertia::render('RegisterSelect'));
Route::get('/register/email', fn () => Inertia::render('RegisterEmail'));

Route::get('/help', fn () => Inertia::render('Help/Index'));
Route::get('/help/taikai', fn () => Inertia::render('Help/Taikai'));


Route::post('/register/email', [RegisterController::class, 'store']);


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/chat/{friend}', function (User $friend) {
        return Inertia::render('Chat', [
            'friend' => $friend,
            'user' => auth()->user()
        ]);
    })->middleware(['auth'])->name('chat');
    Route::get('/messages/{friend}', function (User $friend) {
        return ChatMessage::query()
            ->where(function ($query) use ($friend) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $friend->id);
            })
            ->orWhere(function ($query) use ($friend) {
                $query->where('sender_id', $friend->id)
                    ->where('receiver_id', auth()->id());
            })
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'text' => $message->text,
                    'created_at' => $message->created_at,
                    'image_url' => $message->image_path ? asset('storage/' . $message->image_path) : null,
                ];
            });
        });
        

        Route::post('/messages/{friend}', function (User $friend, Request $request) {
            $request->validate([
                'message' => 'nullable|string|max:2000',
                'image' => ['nullable', 'file', 'image', 'max:10240'], // ← 10MB, 空でも許容
            ]);
        
            $user = auth()->user();
        
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('chat_images', 'public');
            }
            $txt = $request->input('message');
        
            if(!$request->input('message')) {
                $txt = "　";
            }
            $message = ChatMessage::create([
                'sender_id' => $user->id,
                'receiver_id' => $friend->id,
                'text' => $txt,
                'image_path' => $imagePath,
            ]);
        
            broadcast(new MessageSent($message));
        
            $response = $message->toArray();
            $response['image_url'] = $imagePath ? asset('storage/' . $imagePath) : null;
        
            return response()->json($response);
        });
});

