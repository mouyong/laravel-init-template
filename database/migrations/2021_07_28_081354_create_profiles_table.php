<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(config('laravel-init-template.user_foreign_key', 'user_id'))->comment('用户 id：users.id');
 $table->string('realname')->nullable()->comment('用户真实姓名');
            $table->string('mobile')->nullable()->comment('用户手机号');
            $table->string('id_card')->nullable()->comment('用户身份证号');
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
        Schema::dropIfExists('profiles');
    }
}
