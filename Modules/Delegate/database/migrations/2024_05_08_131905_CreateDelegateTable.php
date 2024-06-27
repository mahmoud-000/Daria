<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delegates', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 100);
            $table->string('company_name', 100)->nullable();
            $table->string('email')->nullable();

            $table->tinyInteger('type')->nullable()->default(1);
            $table->tinyInteger('commission_type')->nullable()->default(1);
            $table->float('commission', 10, 4)->nullable()->default(0);

            $table->text('remarks')->nullable();
            $table->boolean('is_active')->nullable()->default(0);
            
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delegates');
    }
};
