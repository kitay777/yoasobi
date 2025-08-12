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
        Schema::table('media', function (Blueprint $table) {
            //
            $table->dropForeign(['advisor_id']);
        // カラム名を変更
        $table->renameColumn('advisor_id', 'user_id');
        // 再度外部キー制約を付与（必要なら）
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            //
            $table->dropForeign(['user_id']);
        $table->renameColumn('user_id', 'advisor_id');
        $table->foreign('advisor_id')->references('id')->on('users')->onDelete('cascade');
    
        });
    }
};
