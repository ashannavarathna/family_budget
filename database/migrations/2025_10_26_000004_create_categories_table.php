<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('transaction_type_id')->unsigned();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
