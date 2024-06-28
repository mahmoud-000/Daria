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
        Schema::create('saleReturns', function (Blueprint $table) {
            $table->id();
            $table->string('doc_invoice_number')->nullable();
            $table->boolean('effected')->default(0);
            $table->date('date');
            $table->float('tax', 10, 4)->default(0);
            // $table->float('tax_net', 10, 4)->default(0);
            $table->float('paid_amount', 10, 4)->default(0);
            $table->tinyInteger('payment_status')->nullable()->default(PaymentStatusEnum::UNPAID);
            $table->float('grand_total', 10, 4);
            $table->tinyInteger('discount_type')->nullable()->default(FPTypesEnum::FIXED);
            $table->float('discount', 10, 4)->nullable()->default(0);
            $table->tinyInteger('commission_type')->nullable()->default(FPTypesEnum::FIXED);
            $table->float('shipping', 10, 4)->nullable()->default(0);
            $table->float('other_expenses', 10, 4)->nullable()->default(0);
            
            $table->foreignId('user_id')->nullable()->constrained();

            $table->foreignId('warehouse_id')->nullable()->constrained();
            $table->foreignId('customer_id')->nullable()->constrained();
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
        Schema::dropIfExists('saleReturns');
    }
};
