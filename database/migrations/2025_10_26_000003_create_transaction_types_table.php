<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTypesTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_types');
    }
}
