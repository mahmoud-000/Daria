<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100);
            $table->string('password');
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->string('email')->nullable();
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();

            $table->text('remarks')->nullable();
            $table->char('gender', 1)->nullable()->default(1);
            $table->boolean('is_active')->nullable()->default(0);
            $table->boolean('send_notify')->nullable()->default(0);
            $table->boolean('is_owner')->nullable()->default(0);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
