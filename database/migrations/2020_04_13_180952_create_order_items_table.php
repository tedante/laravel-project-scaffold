<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('material_id');
            $table->integer('qty');
            $table->text('notes')->nullable();
            $table->decimal('discount_percentage', 2, 2);
            $table->decimal('special_sale_percentage', 2, 2);
            $table->double('price');
            $table->double('price_after_discount');
            $table->double('price_after_sale');
            $table->double('final_price');
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
        Schema::dropIfExists('order_items');
    }
}
