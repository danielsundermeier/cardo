<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('company_name')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('number')->nullable();
            $table->string('postcode')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('mobilenumber')->nullable();
            $table->string('faxnumber')->nullable();
            $table->string('bankname')->nullable();
            $table->string('bic')->nullable();
            $table->string('iban')->nullable();
            $table->string('website')->nullable();

            $table->string('job')->nullable();
            $table->date('birthday_at')->nullable();
            $table->unsignedSmallInteger('height_in_cm')->nullable();
            $table->text('medical_conditions')->nullable();

            $table->boolean('is_client')->default(false);
            $table->boolean('is_staff')->default(false);
            $table->boolean('is_supplier')->default(false);


            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
}
