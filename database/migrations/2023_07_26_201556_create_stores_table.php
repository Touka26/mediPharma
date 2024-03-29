<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales__bill_id')->nullable();
            $table->unsignedBigInteger('medicine_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('quantity_sold')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('total_price')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();


            $table->foreign('sales__bill_id')->references('id')->on('sales__bills')->onDelete('cascade');
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
        Schema::dropIfExists('stores');

    }
};
