<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('type');

            $table->unsignedBigInteger('partner_id')->nullable();

            $table->unsignedInteger('number');
            $table->string('name')->nullable();

            $table->date('date');
            $table->date('date_due')->nullable();

            $table->integer('net')->default(0);
            $table->integer('tax_value')->default(0);
            $table->integer('discount_value')->default(0);
            $table->integer('gross')->default(0);
            $table->integer('outstanding')->default(0);

            $table->timestamps();

            $table->foreign('partner_id')->references('id')->on('partners');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
