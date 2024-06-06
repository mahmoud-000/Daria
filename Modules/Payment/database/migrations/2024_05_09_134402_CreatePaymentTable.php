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
            $table->tinyInteger('type');
            $table->float('received_amount', 10, 0);
            $table->float('amount', 10, 0);
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
