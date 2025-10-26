<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->integer('category_id')->unsigned();
            $table->integer('transaction_type_id')->unsigned();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->date('date');
            $table->string('description', 255)->nullable();
            $table->decimal('amount', 10, 2);
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
