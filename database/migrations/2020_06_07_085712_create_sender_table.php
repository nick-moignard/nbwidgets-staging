<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSenderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->integer('nation_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('manual')->nullable();
            $table->integer('execute')->nullable();
            $table->integer('current')->nullable();
            $table->integer('page')->nullable();
            $table->text('tag')->nullable();
            $table->text('slug')->nullable();
            $table->text('access_token')->nullable();
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
        Schema::dropIfExists('senders');
    }
}
