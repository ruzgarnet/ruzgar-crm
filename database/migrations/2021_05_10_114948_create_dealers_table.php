<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('tax_number', 255);
			$table->string('address', 255);
			$table->foreignId('city_id');
			$table->foreignId('district_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('city_id')
            	->references('id')
            	->on('cities')
            	->onUpdate('CASCADE')
            	->onDelete('CASCADE');
            	
            $table->foreign('district_id')
            	->references('id')
            	->on('districts')
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
        Schema::dropIfExists('dealers');
    }
}
