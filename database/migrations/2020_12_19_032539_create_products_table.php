<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('feature_image');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->integer('rating')->default('0');
            $table->string('brand_name')->nullable();
            $table->string('model_name');
            $table->boolean('block')->default(false)->comment('true=This product have too many report');
            $table->boolean('status')->default(false)->comment('True=Visible & False=Hidden');
            $table->decimal('price',7 ,2);
            $table->decimal('discount_fixed', 7, 2)->nullable();
            $table->integer('discount_percent')->nullable();
            $table->boolean('stock_status')->comment('true=in Stock, False=Out Of Stock');
            $table->integer('view_count')->default('0');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
}
