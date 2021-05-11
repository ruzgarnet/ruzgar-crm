<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_type_id');
            $table->foreignId('customer_id');
            $table->foreignId('staff_id');
            $table->foreignId('service_id');
            $table->unsignedTinyInteger('duration')->nullable()->default(null);
            $table->date('start_at')->nullable()->default(null);
            $table->date('end_at')->nullable()->default(null);
            $table->json('options');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('contract_type_id')
				->references('id')
				->on('contract_types')
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

    		$table->foreign('service_id')
            	->references('id')
            	->on('services')
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
        Schema::dropIfExists('contracts');
    }
}
