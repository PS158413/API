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
        Schema::create('receipt_products', function (Blueprint $table) {
            // values of product
            $table->id();
            $table->integer('article_number')->nullable();
            $table->string('name')->nullable();
            $table->decimal('price')->nullable();
            $table->integer('quantity')->nullable();
            $table->unsignedBigInteger('user_id')->comment('Created By User')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();
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
        //
    }
};
