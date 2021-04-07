<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('price',7,2);
            $table->decimal('discount',7,2)->nullable();
            $table->decimal('shipping_fee',7,2);
            $table->decimal('coupon',7,2)->nullable();
            $table->decimal('total_price',7,2);
            $table->text('shipping_address');
            $table->text('billing_address');
            $table->boolean('payment_status');
            $table->string('payment_type');
            $table->text('delivery_status');
            $table->boolean('delivery_complete')->nullable()->default(null);
            $table->boolean('order_status')->default(false)->nullable()->comment('True=Order Receive, False=Order Pending, Null=Order Cancel');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
