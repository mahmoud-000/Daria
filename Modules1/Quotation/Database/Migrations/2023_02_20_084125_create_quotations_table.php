<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->boolean('effected')->default(0);
            $table->date('date');
            $table->string('ref')->nullable();
            $table->float('tax', 10, 4)->default(0);
            $table->float('tax_net', 10, 4)->default(0);
            $table->float('paid_amount', 10, 4)->default(0);
            $table->float('grand_total', 10, 4);
            $table->float('discount', 10, 4)->default(0);
            $table->tinyInteger('shipping_type');
            $table->float('shipping', 10, 4)->nullable()->default(0);

            $table->text('remarks')->nullable();
            $table->boolean('is_active')->nullable()->default(0);

            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotations');
    }
};
