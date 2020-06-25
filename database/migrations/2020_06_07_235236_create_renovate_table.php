<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenovateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renovates', function (Blueprint $table) {
            $table->id();
            $table->integer('nation_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('execute')->nullable();
            $table->integer('no_members')->nullable();
            $table->integer('no_nomembers')->nullable();
            
            $table->text('next_url')->nullable();
            $table->text('slug')->nullable();
            $table->text('access_token')->nullable();
            $table->text('url')->nullable();
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
        Schema::dropIfExists('renovates');
    }
}
