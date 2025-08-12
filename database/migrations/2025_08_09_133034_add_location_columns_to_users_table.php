<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // 緯度・経度（~1.1cm 精度）
            $table->decimal('lat', 10, 7)->nullable()->index();
            $table->decimal('lng', 10, 7)->nullable()->index();

            // 共有ON/OFF・更新時刻
            $table->boolean('share_location')->default(true)->index();
            $table->timestamp('location_updated_at')->nullable()->index();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lat','lng','share_location','location_updated_at']);
        });
    }
};
