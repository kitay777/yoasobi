<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('advisor_likes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('advisor_id')->constrained('users')->onDelete('cascade'); // アドバイザーは users テーブルの一部とする場合
        $table->timestamps();

        $table->unique(['user_id', 'advisor_id']); // 同じ相手を2回いいねできないようにする
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisor_likes');
    }
};
