<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->integer('id_applications', 11);
            $table->integer('id_user');
            $table->integer('id_icons');
            $table->string('name_applications');
            $table->string('package_applications');
            $table->string('key_applications');
            $table->string('limit_applications');
            // $table->enum('status', ['limit','available']);
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
        Schema::dropIfExists('applications');
    }
}
