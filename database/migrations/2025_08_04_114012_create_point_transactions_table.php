<?php

// database/migrations/xxxx_xx_xx_create_point_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points'); // 加算されたポイント
            $table->integer('amount'); // 金額（円）
            $table->string('stripe_session_id')->nullable();
            $table->string('type')->default('purchase'); // 'purchase', 'admin_adjustment', etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('point_transactions');
    }
}
