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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('lesson_name', 255);
            $table->foreignId('course_id')->constrained('courses');
            $table->text('content')->nullable();
            $table->string('video', 255)->nullable();
            $table->foreignId('created_by_id')->constrained('users');
            $table->foreignId('modified_by_id')->constrained('users');
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
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['modified_by_id']);
        });
        Schema::dropIfExists('lessons');
    }
};
