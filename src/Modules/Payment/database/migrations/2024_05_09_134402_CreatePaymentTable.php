<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('paymentable');
            $table->date('date');
            $table->unsignedTinyInteger('type');
            $table->unsignedInteger('received_amount')->default(0);
            $table->unsignedInteger('amount')->default(0);
            $table->text('note')->nullable();

            $table->foreignId('user_id')->nullable()->constrained();
            
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
