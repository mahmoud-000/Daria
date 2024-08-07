<?php

use App\Enums\ItemTypesEnum;
use App\Enums\ProductTypesEnum;
use App\Enums\TaxTypesEnum;
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
            $table->unsignedTinyInteger('product_type')->default(ProductTypesEnum::STOCK_ITEM);
            $table->unsignedTinyInteger('type')->default(ItemTypesEnum::STANDARD);
            $table->date('production_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->unsignedInteger('amount');
            $table->unsignedInteger('tax')->default(0);
            $table->unsignedTinyInteger('tax_type')->default(TaxTypesEnum::EXCLUSIVE);
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedTinyInteger('discount_type')->default(1);
            
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('quantity')->default(1);
            
            $table->foreignId('unit_id')->nullable()->constrained();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('variant_id')->nullable()->constrained();
            $table->foreignId('patch_id')->nullable()->constrained();
            
            $table->unsignedTinyInteger('movement')->nullable()->default(1);
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('details');
    }
};
