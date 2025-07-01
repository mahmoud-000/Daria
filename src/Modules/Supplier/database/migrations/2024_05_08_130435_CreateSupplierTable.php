<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 100);
            $table->string('company_name', 100)->nullable();
            $table->string('email')->nullable();

            $table->text('remarks')->nullable();
            $table->boolean('is_active')->nullable()->default(0);
            $table->tinyInteger('type')->nullable()->default(1);
            
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};
