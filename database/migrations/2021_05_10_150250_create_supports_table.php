<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_types', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->nullable()->default(null);
            $table->string('name');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);
        });

        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_type_id');
            $table->foreignId('customer_id');
            $table->foreignId('staff_id');
            $table->timestamp('seen_at')->nullable()->default(null);
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

			$table->foreign('support_type_id')
				->references('id')
				->on('support_types')
				->onUpdate('CASCADE')
				->onDelete('CASCADE');
				
            $table->foreign('customer_id')
            	->references('id')
            	->on('customers')
            	->onUpdate('CASCADE')
            	->onDelete('CASCADE');
            	
            $table->foreign('staff_id')
            	->references('id')
            	->on('staffs')
            	->onUpdate('CASCADE')
            	->onDelete('CASCADE');
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_id');
            $table->unsignedTinyInteger('owner');
            $table->tinyText('message');
            $table->timestamp('created_at')->useCurrent();

			$table->foreign('support_id')->references('id')->on('supports')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::dropIfExists('support_messages');
    	Schema::dropIfExists('supports');
        Schema::dropIfExists('support_types');
    }
}
