<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function(Blueprint $table) {
            $table->id();
            $table->integer('unit_id');
            $table->integer('category_id');
            $table->integer('material_type_id')->nullable();
            $table->string('material_group')->nullable();
            $table->string('name');
            $table->string('code');
            $table->string('excerpt')->nullable();
            $table->string('description')->nullable();
            $table->string('document_link')->nullable();
            $table->string('plant')->nullable();
            $table->decimal('prices', 12, 2);
            $table->decimal('discount_percentage', 2, 2);
            $table->boolean('is_active');
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
        Schema::dropIfExists('materials');
    }
}
