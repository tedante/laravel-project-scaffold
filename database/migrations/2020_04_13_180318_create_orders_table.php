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
            $table->string('order_number');
            $table->integer('user_id');

            $table->string('address_name');
            $table->string('address');
            $table->string('postal_code');
            $table->string('village');
            $table->string('district');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('receiver_name');
            $table->string('phone_number');

            $table->integer('storage_location_id');
            $table->string('status');
            $table->string('sales_order')->nullable();
            $table->string('expedition_name')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('delivery_type')->nullable();
            $table->string('payment_method');
            $table->double('subtotal_amount');
            $table->double('total_amount');
            $table->double('discount_partner');
            $table->decimal('discount_partner_percentage', 2, 2);
            $table->softDeletes();
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
