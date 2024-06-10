<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->float('quantity', 10, 0)->nullable()->default(0);
            
            $table->foreignId('item_id')->constrained();
            $table->foreignId('variant_id')->nullable()->constrained();
            $table->foreignId('warehouse_id')->constrained();
            
            $table->json('details')->nullable();
            
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};
