<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('content_types', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->nullable()->default(null);
            $table->string('title', 255);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);
        });

        Schema::create('content_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_type_id');
            // $table->string('key')->unique()->nullable()->default(null);
            $table->foreignId('parent_id')->nullable()->default(null);
            $table->string('title', 255);
            // $table->string('slug', 255)->unique();
            // $table->text('content');
            // $table->string('meta_title', 255);
            // $table->string('meta_description', 255);
            // $table->string('meta_keywords', 255);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('content_type_id')
        		->references('id')
        		->on('content_types')
        		->onUpdate('CASCADE')
        		->onDelete('CASCADE');
        });

        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_type_id');
            // $table->string('key')->unique()->nullable()->default(null);
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('content');
            $table->string('meta_title', 255);
            $table->string('meta_description', 255);
            $table->string('meta_keywords', 255);
            $table->json('options')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);
			
			$table->foreign('content_type_id')
        		->references('id')
        		->on('content_types')
        		->onUpdate('CASCADE')
        		->onDelete('CASCADE');
        });

        Schema::create('category_content', function (Blueprint $table) {
        	$table->foreignId('category_id');
        	$table->foreignId('content_id');

        	$table->primary(['category_id', 'content_id']);

        	$table->foreign('category_id')
        		->references('id')
        		->on('categories')
        		->onUpdate('CASCADE')
        		->onDelete('CASCADE');

        	$table->foreign('content_id')
        		->references('id')
        		->on('contents')
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
        Schema::dropIfExists('category_content');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('content_categories');
        Schema::dropIfExists('content_types');
    }
}
