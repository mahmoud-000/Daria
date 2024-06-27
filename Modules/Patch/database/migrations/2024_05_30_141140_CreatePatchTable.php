<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patches', function (Blueprint $table) {
            $table->id();
            $table->date('production_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->float('quantity', 10, 4)->nullable()->default(0);
            $table->float('amount', 10, 4)->nullable()->default(0);

            $table->foreignId('stock_id')->constrained('stock');
            $table->foreignId('item_id')->constrained();
            $table->foreignId('variant_id')->nullable()->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('unit_id')->constrained();

            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patchs');
    }
};
