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
        Schema::create('purchases_bills_medicines', function (Blueprint $table) {
            $table->id();
          //  $table->integer('purchases_bill_medicine_id')->unique();
            $table->integer('medicine_id')->unsigned();
            $table->integer('purchases_bill_id')->unsigned();

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
        Schema::dropIfExists('purchases_bills_medicines');
    }
};
