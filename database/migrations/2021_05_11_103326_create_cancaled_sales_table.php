<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancaledSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancaled_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id');
            $table->string('description');
            $table->foreignId('staff_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('sale_id')
				->references('id')
				->on('sales')
				->onUpdate('CASCADE')
				->onDelete('CASCADE');

			$table->foreign('staff_id')
				->references('id')
				->on('staffs')
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
        Schema::dropIfExists('cancaled_sales');
    }
}
