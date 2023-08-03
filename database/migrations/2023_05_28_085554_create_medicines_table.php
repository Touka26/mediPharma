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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
           // $table->integer('medicine_id')->unique();
            $table->unsignedBigInteger('manufacture_id');
            $table->string('barcode');
            $table->string('trade_name');
            $table->string('combination');
            $table->string('caliber');
            $table->string('type');
            $table->string('pharmaceutical_form');
            $table->double('net_price');
            $table->double('common_price');
//            $table->double('total_price');
            $table->integer('amount');
            $table->boolean('statement');
            $table->string('prescription_url')->nullable();
            $table->integer('id_number')->unique()->nullable();
            $table->string('image_url');
            $table->date('production_date');
            $table->date('expiration_date');
            $table->timestamps();

          //  $table->foreign('manufacture_id')->references('id')->on('manufactures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicines');
    }
};
