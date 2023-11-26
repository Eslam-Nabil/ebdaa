<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('expense_requests');
        Schema::create('expense_requests', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('expenses_id');
            $table->foreign('expenses_id')->references('id')->on('expenses');

            $table->integer('amount');

            $table->unsignedInteger('createdBy');
            $table->foreign('createdBy')->references('id')->on('users');

            $table->unsignedInteger('acceptedBy')->nullable();
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
        Schema::dropIfExists('expense_requests');
    }
}