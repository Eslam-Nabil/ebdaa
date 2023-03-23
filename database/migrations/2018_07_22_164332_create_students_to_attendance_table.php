<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsToAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_to_attendance', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attendance_id');
            $table->unsignedInteger('application_id');
            $table->timestamps();

            $table->foreign('attendance_id')->references('id')->on('attendance_to_course');
            $table->foreign('application_id')->references('id')->on('applications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_to_attendance');
    }
}
