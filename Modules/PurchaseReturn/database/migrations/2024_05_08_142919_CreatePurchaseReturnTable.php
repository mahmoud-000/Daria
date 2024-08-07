<?php

use App\Enums\PaymentStatusEnum;
use App\Enums\FPTypesEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchaseReturns', function (Blueprint $table) {
            $table->id();
            $table->string('doc_invoice_number')->nullable();
            $table->boolean('effected')->default(0);
            $table->date('date');
            $table->unsignedInteger('tax')->default(0);
            $table->unsignedInteger('paid_amount')->default(0);
            $table->unsignedTinyInteger('payment_status')->nullable()->default(PaymentStatusEnum::UNPAID);
            $table->unsignedInteger('grand_total');
            $table->unsignedTinyInteger('discount_type')->nullable()->default(FPTypesEnum::FIXED);
            $table->unsignedInteger('discount')->nullable()->default(0);
            $table->unsignedTinyInteger('commission_type')->nullable()->default(FPTypesEnum::FIXED);
            $table->unsignedInteger('shipping')->nullable()->default(0);
            $table->unsignedInteger('other_expenses')->nullable()->default(0);
            
            $table->foreignId('user_id')->nullable()->constrained();

            $table->foreignId('warehouse_id')->nullable()->constrained();
            $table->foreignId('supplier_id')->nullable()->constrained();
            $table->foreignId('delegate_id')->nullable()->constrained();
            $table->foreignId('pipeline_id')->nullable()->constrained();
            $table->foreignId('stage_id')->nullable()->constrained();

            $table->text('remarks')->nullable();

            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchaseReturns');
    }
};
