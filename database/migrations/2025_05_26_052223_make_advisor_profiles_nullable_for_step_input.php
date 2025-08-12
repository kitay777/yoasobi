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
        //
        Schema::table('advisor_profiles', function (Blueprint $table) {
    $table->string('name', 50)->nullable()->change();
    $table->integer('age')->nullable()->change();
    $table->string('region', 50)->nullable()->change();
    // 他に該当する項目があれば同様に
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
