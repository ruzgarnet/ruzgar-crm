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
            $table->foreignId('category_id');
            $table->string('name', 255);
            $table->string('model', 255)->unique();
            $table->string('slug', 255)->unique();
            $table->unsignedDecimal('price');
            $table->text('content');
            $table->string('meta_title', 255)->nullable()->default(null);
            $table->string('meta_description', 255)->nullable()->default(null);
            $table->string('meta_keywords', 255)->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('category_id')
        		->references('id')
        		->on('categories')
        		->onUpdate('CASCADE')
        		->onDelete('CASCADE');
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
