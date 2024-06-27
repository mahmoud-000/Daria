<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code')->nullable();
            $table->string('sku')->nullable();
            $table->float('cost', 10, 4)->nullable()->default(0);
            $table->float('price', 10, 4)->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(0);
            $table->text('remarks')->nullable();
            $table->foreignId('item_id')->constrained();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variants');
    }
};
