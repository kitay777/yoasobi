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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('phone_number');
        $table->string('bank_name');
        $table->string('bank_code')->nullable();
        $table->string('branch_name');
        $table->string('branch_code')->nullable();
        $table->enum('account_type', ['普通', '当座']);
        $table->string('account_number');
        $table->string('account_holder_sei');
        $table->string('account_holder_mei');
        $table->string('invoice_number')->nullable();
        $table->boolean('is_default')->default(false);
        $table->json('consents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
