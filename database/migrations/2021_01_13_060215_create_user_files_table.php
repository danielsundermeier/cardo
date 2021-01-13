<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');

            $table->nullableMorphs('fileable');

            $table->string('name');
            $table->string('extension');
            $table->string('original_name');
            $table->string('filename');
            $table->string('mime');
            $table->integer('size')->default(0);
            $table->boolean('thumbnail')->default(false);
            $table->boolean('preview')->default(false);

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
        Schema::dropIfExists('user_files');
    }
}
