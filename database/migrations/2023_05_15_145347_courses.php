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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name', 255);
            $table->unsignedFloat('price');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id'); //Trường khóa ngoại
            $table->smallInteger('status')->index();
            $table->string('image', 255)->nullable();
            $table->unsignedBigInteger('created_by_id'); //Trường khóa ngoại
            $table->unsignedBigInteger('modified_by_id'); //Trường khóa ngoại
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('created_by_id') // Khóa ngoại
              ->references('id')
              ->on('users')
              ->onDelete('cascade');

            $table->foreign('modified_by_id') // Khóa ngoại
              ->references('id')
              ->on('users')
              ->onDelete('cascade');

            $table->foreign('category_id') // Khóa ngoại
              ->references('id')
              ->on('categories')
              ->onDelete('cascade');

        });

        // Schema::table('courses', function (Blueprint $table){
            
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table){
            $table->dropForeign(['created_by_id', 'modified_by_id', 'category_id']); 
        });
        Schema::dropIfExists('courses');
    }
};
