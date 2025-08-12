<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable; // ← これを追加！
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTestMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Mail::raw('これは SendTestMail ジョブから送られたテストメールです。', function ($message) {
            $message->to('kitayama@kitayama.jp')->subject('キュー経由テストメール');
        });
    }
}
