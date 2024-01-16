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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // sku of product
            $table->string('sku')->unique();
            $table->integer('article_number');
            $table->string('name');
            $table->string('description');
            $table->decimal('price');
            $table->string('image')->nullable();
            $table->string('color');
            $table->integer('height_cm');
            $table->integer('width_cm');
            $table->integer('depth_cm');
            $table->integer('weight_gr');
            $table->string('barcode');
            $table->integer('stock');
            $table->unsignedBigInteger('user_id')->comment('Created By User');
            $table->timestamps();

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
        Schema::dropIfExists('products');
    }
};
