<?php

use App\Enums\ActiveEnum;
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
            $table->unsignedInteger('cost')->nullable()->default(0);
            $table->unsignedInteger('price')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(ActiveEnum::NOTACTIVED);
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
