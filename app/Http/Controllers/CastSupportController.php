<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\CastSupport;
use Illuminate\Support\Facades\Log;

class CastSupportController extends Controller
{
    //
    // app/Http/Controllers/CastSupportController.php

    public function create()
    {
        return Inertia::render('Cast/SupportInput');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => ['nullable', 'string', 'min:10', 'max:1000'],
        ]);

        if ($request->filled('content')) {
            CastSupport::create([
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
            ]);
        }

        return redirect()->route('dashboard'); // ← スキップでも次へ進む // ← 次ステップへ
    }

}
