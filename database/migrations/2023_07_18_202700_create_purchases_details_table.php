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
    public function up()
    {

        Schema::create('purchases__details', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('purchases__bill_id'); // Change to unsigned big integer
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('medicine_id')->nullable();
            $table->integer('amount');
            $table->double('unit_price');
            $table->double('total_price');
            $table->timestamps();

            $table->foreign('purchases__bill_id')->references('id')->on('purchases__bills');
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->foreign('product_id')->references('id')->on('products');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases_details');
    }
};
