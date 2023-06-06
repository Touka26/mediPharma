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
        Schema::create('pharmacists', function (Blueprint $table) {
            $table->id();
           // $table->integer('pharmacist_id')->unique();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->integer('registration_number');
            $table->date('registration_date');
            $table->date('released_on_date');
            $table->string('city');
            $table->string('region');
            $table->string('name_of_pharmacy');
            $table->integer('landline_phone_number');
            $table->integer('mobile_number');
            $table->string('copy_of_the_syndicate_card_url');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('password_confirmation');
            $table->string('image_url');
            $table->double('financial_fund');
           // $table->boolean('status')->nullable();
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
        Schema::dropIfExists('pharmacists');
    }
};
