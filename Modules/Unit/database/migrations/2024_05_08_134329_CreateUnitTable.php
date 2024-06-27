<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('short_name', 50);
            $table->char('operator', 1)->nullable()->default('*');
            $table->float('operator_value', 10, 4)->nullable()->default(1);
            $table->foreignId('unit_id')->nullable()->constrained();
            $table->text('remarks')->nullable();
            $table->boolean('is_active')->nullable()->default(0);

            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};
