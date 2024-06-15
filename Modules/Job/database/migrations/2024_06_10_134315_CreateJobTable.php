<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_titles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->float('min_salary', 10, 0)->nullable()->default(0);
            $table->float('max_salary', 10, 0)->nullable()->default(0);
            $table->text('remarks')->nullable();
            $table->boolean('is_active')->nullable()->default(0);

            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
