<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('province')->comment('商户所在省');
            $table->string('city')->comment('商户所在市');
            $table->string('county')->comment('商户所在区县');
            $table->string('address')->comment('商户地址');
            $table->string('longitude')->comment('经度');
            $table->string('latitude')->comment('纬度');
            $table->morphs('addressable');
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
        Schema::dropIfExists('addresses');
    }
}
