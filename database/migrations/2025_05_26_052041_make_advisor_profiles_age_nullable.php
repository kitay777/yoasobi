<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('advisor_profiles', function (Blueprint $table) {
            $table->integer('age')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('advisor_profiles', function (Blueprint $table) {
            $table->integer('age')->nullable(false)->change();
        });
    }
};
