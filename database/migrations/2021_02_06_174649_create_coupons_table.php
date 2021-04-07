<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('coupon')->unique();
            $table->integer('usages')->nullable()->comment('Number=One User That Number Time Use This Coupon & null=Unlimited Use');
            $table->integer('total_use')->default('0');
            $table->string('image');
            $table->decimal('target_price', 7, 2)->default(0);
            $table->decimal('fixed_offer', 7, 2)->nullable();
            $table->decimal('percent_offer',2,0)->nullable();
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('coupons');
    }
}
