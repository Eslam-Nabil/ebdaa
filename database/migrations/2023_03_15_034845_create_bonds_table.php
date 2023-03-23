<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->integer('amount');
            
            $table->integer('income_id');
            $table->foreign('income_id')->references('id')->on('incomes');

            $table->integer('course_id')->nullable();
            $table->foreign('course_id')->nullable()->references('id')->on('courses');
           
            $table->string('user');

            $table->integer('createdBy');
            $table->foreign('createdBy')->references('id')->on('users');

            $table->integer('acceptedBy');
            $table->foreign('acceptedBy')->references('id')->on('users');
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
        Schema::dropIfExists('bonds');
    }
}
