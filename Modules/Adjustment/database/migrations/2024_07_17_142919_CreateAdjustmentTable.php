<?php

use App\Enums\PaymentStatusEnum;
use App\Enums\FPTypesEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('adjustments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->boolean('effected')->default(0);
            $table->unsignedInteger('items');
            $table->float('grand_total', 10, 4);

            $table->foreignId('user_id')->nullable()->constrained();
            
            $table->foreignId('warehouse_id')->nullable()->constrained();
            $table->foreignId('pipeline_id')->nullable()->constrained();
            $table->foreignId('stage_id')->nullable()->constrained();

            $table->text('remarks')->nullable();

            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('adjustments');
    }
};
