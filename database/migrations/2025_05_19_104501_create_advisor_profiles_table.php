<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('advisor_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        $table->string('name');                  // アカウント名
        $table->integer('age');
        $table->string('region');
        $table->string('tags')->nullable();      // スペース区切りなど
        $table->text('bio')->nullable();         // 自己紹介

        $table->integer('subscription_price')->nullable(); // サブスク料金（円）
        $table->integer('talk_price')->nullable();         // トーク料金（ポイント）

        $table->string('avatar_path')->nullable(); // プロフィール画像
        $table->string('cover_path')->nullable();  // 背景画像

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisor_profiles');
    }
};
