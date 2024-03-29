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
        Schema::create('sales__details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales__bill_id');
            $table->integer('medicine_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('name');
            $table->integer('quantity_sold');
            $table->double('unit_price');
            $table->double('total_price');
            $table->string('image_url');
            $table->timestamps();

            $table->foreign('sales__bill_id')
                ->references('id')
                ->on('sales__bills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_details');
    }
};
