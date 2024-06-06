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
            $table->float('cost', 10, 0)->nullable()->default(0);
            $table->float('price', 10, 0)->nullable()->default(0);
            // $table->boolean('default')->default(0)->nullable();
            $table->string('color')->nullable();
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
