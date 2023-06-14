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
        Schema::create('import_notice', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->boolean('status');
            $table->foreignId('user_id')->constrained('users');
            $table->text('notification')->nullable();
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
        Schema::table('import_notice', function (Blueprint $table){
            $table->dropForeign(['user_id']); 
        });
        Schema::dropIfExists('import_notice');
    }
};
