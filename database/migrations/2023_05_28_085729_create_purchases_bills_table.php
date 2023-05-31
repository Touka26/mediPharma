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
        Schema::create('purchases_bills', function (Blueprint $table) {
            $table->id();
           // $table->integer('purchases_bill_id')->unique();
            $table->integer('pharmacist_id')->unsigned();
            $table->date('today\'s date');
            $table->string('material_name');
            $table->integer('all_amount');
            $table->double('unit_price');
            $table->double('total_price');
            $table->string('storehouse_name');
            $table->string('Statement');
            $table->string('image_url');
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
        Schema::dropIfExists('purchases_bills');
    }
};
