<?php

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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('merchant_id');
            // TODO: Replace me with a brief explanation of why floats aren't the correct data type, and replace with the correct data type.
            // commission rate / any kind of price should be typed as double instead of float
            // because float have limited data values to store where as double have maximum capacity to store 
            // large amount of data with more precise values than float
            //New Columns as per the data param in reference comments of service class
            //As follows 
            /**
             * Create a new affiliate for the merchant with the given commission rate.
             *
             * @param  Merchant $merchant
             * @param  string $email
             * @param  string $name
             * @param  float $commissionRate
             * @return Affiliate
             */
            $table->double('commission_rate')->default(0);
            $table->string('discount_code');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('affiliates');
    }
};
