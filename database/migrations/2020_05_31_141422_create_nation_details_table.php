<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nation_details', function (Blueprint $table) {
            $table->id();
            $table->integer('theme')->default(0);
            $table->string('tag',100);
            $table->integer('nation_id');
            $table->string('show_options')->default('{"first_name": 1,"last_name": 1,"city": 1,"country": 1,"address": 1,"email": 1,"phone": 1,"assist_name": 0,"assist_email": 0,"assist_phone": 0}');
            $table->text('intro')->nullable();
            $table->text('disclaimer')->nullable();
            $table->string('report_color')->nullable();
            $table->integer('hq')->default(0);
            $table->string('membership_sync')->nullable();
            $table->integer('sync_picture')->default(0);
            $table->string('picture_sync')->nullable();
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
        Schema::dropIfExists('nation_details');
    }
}
