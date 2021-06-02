<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_type_id');
            $table->unsignedTinyInteger('type');
            $table->string('key', 255)->unique()->nullable()->default(null);
            $table->foreignId('parent_id')->nullable()->default(null);
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('content');
            $table->json('options')->nullable()->default(null);
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('meta_title', 255)->nullable()->default(null);
            $table->string('meta_description', 255)->nullable()->default(null);
            $table->string('meta_keywords', 255)->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('contract_type_id')
				->references('id')
				->on('contract_types')
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
        Schema::dropIfExists('categories');
    }
}
