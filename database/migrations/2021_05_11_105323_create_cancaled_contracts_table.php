<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancaledContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancaled_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id');
            $table->string('description');
            $table->foreignId('staff_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('contract_id')
				->references('id')
				->on('contracts')
				->onUpdate('CASCADE')
				->onDelete('CASCADE');

			$table->foreign('staff_id')
				->references('id')
				->on('staff')
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
        Schema::dropIfExists('cancaled_contracts');
    }
}
