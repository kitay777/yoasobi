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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
                $table->foreignId('cast_id')->constrained('users')->comment('キャストID');
                $table->foreignId('advisor_id')->constrained('users')->comment('アドバイザーID');
                $table->integer('price')->comment('この契約時点の金額');
                $table->date('start_date')->comment('契約開始日');
                $table->date('end_date')->nullable()->comment('契約終了日（nullなら継続中）');
                $table->string('status')->default('active')->comment('active/ended/cancelled等');
                $table->text('cancel_reason')->nullable()->comment('解約理由');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
