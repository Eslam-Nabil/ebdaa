<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsToCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_to_course', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('application_id');
            $table->float('paid', 8, 2)->nullable();
            $table->string('invoice')->nullable();
            $table->string('discount')->nullable();
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')->on('courses');
            $table->foreign('application_id')
                ->references('id')->on('applications');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_to_course');
    }
}
