<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();

            $table->decimal('quantity', 12, 4)->default(0);
            $table->decimal('unit_price', 15, 6)->default(0);
            $table->decimal('discount', 4, 3)->default(0);
            $table->integer('net')->default(0);
            $table->decimal('tax', 4, 3)->default(0);
            $table->integer('gross')->default(0);
            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lines');
    }
}
