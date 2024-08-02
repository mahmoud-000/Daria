<?php

use App\Enums\ProductTypesEnum;
use App\Enums\ItemTypesEnum;
use App\Enums\ActiveEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('label', 100)->nullable();
            $table->string('item_desc', 255)->nullable();
            $table->unsignedInteger('cost')->nullable()->default(0);
            $table->unsignedInteger('price')->nullable()->default(0);

            // $table->char('channel', 1)->nullable();

            $table->string('sku')->nullable();
            $table->string('code')->nullable();
            $table->tinyInteger('barcode_type')->nullable();

            $table->text('remarks')->nullable();

            $table->unsignedInteger('tax')->nullable()->default(0);
            $table->tinyInteger('tax_type')->nullable();
            $table->tinyInteger('stock_alert')->nullable()->default(0);

            $table->tinyInteger('type')->nullable()->default(ItemTypesEnum::STANDARD);
            $table->tinyInteger('product_type')->nullable()->default(ProductTypesEnum::STOCK_ITEM);
            
            $table->boolean('is_active')->nullable()->default(ActiveEnum::NOTACTIVED);
            $table->boolean('is_available_for_purchase')->nullable()->default(ActiveEnum::NOTACTIVED);
            $table->boolean('is_available_for_sale')->nullable()->default(ActiveEnum::NOTACTIVED);
            $table->boolean('is_available_for_edit_in_purchase')->nullable()->default(ActiveEnum::NOTACTIVED);
            $table->boolean('is_available_for_edit_in_sale')->nullable()->default(ActiveEnum::NOTACTIVED);

            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->foreignId('sale_unit_id')->nullable()->constrained('units');
            $table->foreignId('purchase_unit_id')->nullable()->constrained('units');

            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('brand_id')->nullable()->constrained();

            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
