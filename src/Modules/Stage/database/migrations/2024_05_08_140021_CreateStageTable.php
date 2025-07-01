<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedInteger('complete');
            $table->boolean('is_default')->default(0)->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->nullable()->default(0);
            $table->text('remarks')->nullable();
            $table->foreignId('pipeline_id')->constrained();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stages');
    }
};
