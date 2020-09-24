<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->unsignedBigInteger('completer_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();

            $table->boolean('is_completed')->default(false);
            $table->dateTime('is_completed_at')->nullable();

            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->unsignedTinyInteger('priority')->default(0);

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('task_category');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('staff_id')->references('id')->on('partners');
            $table->foreign('completer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
