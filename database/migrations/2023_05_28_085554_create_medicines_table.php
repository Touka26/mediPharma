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
            $table->integer('manufacture_id')->unsigned();
            $table->string('barcode');
            $table->string('trade_name');
            $table->string('combination');
            $table->string('caliber');
            $table->string('type');
            $table->string('pharmaceutical_form');
            $table->double('common_price');
            $table->integer('amount');
            $table->boolean('statement');
            $table->string('prescription_url');
            $table->integer('id_number')->unique();
            $table->string('image_url');
            $table->date('production_date');
            $table->date('expiration_date');
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
        Schema::dropIfExists('medicines');
    }
};
