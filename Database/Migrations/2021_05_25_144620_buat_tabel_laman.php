<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuatTabelLaman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laman', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');

            $table->string('label')->nullable();
            $table->string('slug')->nullable();
            
            $table->longText('content')->nullable();
            $table->integer('id_admin')->nullable();
            $table->integer('profil')->nullable();

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
        Schema::dropIfExists('laman');
    }
}
