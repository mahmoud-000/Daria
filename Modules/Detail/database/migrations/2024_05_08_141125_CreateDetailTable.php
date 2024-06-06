<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->morphs('detailable');
            $table->unsignedInteger('product_type')->default(1);
            $table->date('production_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->float('amount', 10, 0);
            $table->float('tax', 10, 0)->default(0);
            $table->tinyInteger('tax_type')->default(1);
            $table->float('discount', 10, 0)->default(0);
            $table->unsignedInteger('discount_type')->default(1);
            $table->float('total', 10, 0)->default(0);
            $table->float('quantity', 10, 0)->default(1);

            $table->foreignId('unit_id')->constrained();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('variant_id')->nullable()->constrained();
            $table->foreignId('patch_id')->nullable()->constrained();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('details');
    }
};
