<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->integer('id_images', 11);
            $table->integer('id_applications');
            $table->integer('id_categories');
            $table->string('folder');
            $table->string('name');
            $table->string('extension');
            $table->enum('status', ['url', 'assets']);
            $table->enum('position', ['public', 'crauser']);
            $table->text('url');
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
        Schema::dropIfExists('images');
    }
}
