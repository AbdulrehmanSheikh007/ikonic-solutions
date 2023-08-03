<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained();
            $table->foreignId('affiliate_id')->nullable()->constrained();
            // TODO: Replace floats with the correct data types (very similar to affiliates table)
            // commission rate / any kind of price should be typed as double instead of float
            // because float have limited data values to store where as double have maximum capacity to store 
            // large amount of data with more precise values than float
            $table->double('subtotal')->default(0);
            $table->double('commission_owed')->default(0.00);
            $table->string('payout_status')->default(Order::STATUS_UNPAID);
            $table->string('discount_code')->nullable();
            /*
             * Adding new columns as per the Order Service class data params as follows
             * @param  array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, 
             * customer_email: string, customer_name: string} $data
             * 
             * I can create a separate migration as well if the migration already run for this class
             * But just creating new columns in same migration
             */
            $table->string('order_id')->nullable();
            $table->string('merchant_domain')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('orders');
    }
};
