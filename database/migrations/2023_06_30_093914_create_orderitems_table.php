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
        Schema::create('orderitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Created By Logged in User')->nullable();
            $table->unsignedBigInteger('order_id')->comment('Created By not Logged in User')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orderitems');
    }
};
