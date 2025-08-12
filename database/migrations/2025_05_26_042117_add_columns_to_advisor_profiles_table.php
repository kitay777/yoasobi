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
    Schema::table('advisor_profiles', function (Blueprint $table) {
        $table->text('introduction')->nullable()->after('bio');
        $table->text('experience')->nullable()->after('introduction');
        $table->string('status')->default('draft')->after('experience');
        $table->boolean('is_active')->default(true)->after('status');
    });
}

public function down(): void
{
    Schema::table('advisor_profiles', function (Blueprint $table) {
        $table->dropColumn(['introduction', 'experience', 'status', 'is_active']);
    });
}

};
