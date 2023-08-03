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
        Schema::create('sales__bills', function (Blueprint $table) {
            $table->id();
         //   $table->integer('sales_bill_id')->unique();
            $table->unsignedBigInteger('pharmacist_id');
            $table->date('today_date');
            $table->timestamps();

            $table->foreign('pharmacist_id')->references('id')->on('pharmacists')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_bills');
    }
};
