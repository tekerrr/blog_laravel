<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigTable extends Migration
{
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key');
            $table->string('value')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('config');
    }
}
