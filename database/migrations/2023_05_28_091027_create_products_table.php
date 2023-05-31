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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
          //  $table->integer('product_id')->unique();
            $table->integer('category_id')->unsigned();
            $table->string('barcode');
            $table->string('name');
            $table->string('type');
            $table->string('combination')->nullable();
            $table->string('caliber')->nullable();
            $table->integer('amount');
            $table->double('common_price');
            $table->double('total_price');
            $table->string('image_url');
            $table->date('production_date')->nullable();
            $table->date('expiration_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
