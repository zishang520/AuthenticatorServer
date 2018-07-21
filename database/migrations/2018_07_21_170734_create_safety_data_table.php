<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSafetyDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safety_data', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id'); // id
            $table->string('user_uid', 120)->nullable(false)->default(''); // user_uid 加密后的openid
            $table->longText('encrypt_data'); // 加密后的数据
            $table->timestamps();
            $table->unique('user_uid', 'unique_uid');
            $table->foreign('user_uid')->references('uid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('safety_data');
    }
}
