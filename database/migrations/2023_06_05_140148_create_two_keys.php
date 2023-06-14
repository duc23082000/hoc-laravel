<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_keys', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 50);
            $table->foreignId('user_id')->constrained('users');
            $table->string('otp')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::table('two_keys', function (Blueprint $table){
            $table->dropForeign(['user_id']); 
        });
        Schema::dropIfExists('two_keys');
    }
};
