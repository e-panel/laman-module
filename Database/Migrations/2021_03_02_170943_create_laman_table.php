<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laman', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('label')->nullable();
            $table->string('slug')->nullable();
            $table->longText('content')->nullable();

            $table->uuid('user_id')->nullable();
            $table->integer('active')->nullable();

            $table->timestamps();
            $table->softDeletes();
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
