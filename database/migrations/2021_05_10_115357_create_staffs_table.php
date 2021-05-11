<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id');
            $table->string('customer_no', 31)->unique();
            $table->string('identification_number', 15)->unique();
            $table->string('first_name', 63);
            $table->string('last_name', 63);
            $table->string('phone', 15);
            $table->string('email', 31);
            $table->string('second_phone', 15)->nullable()->default(null);
            $table->date('birthday');
            $table->string('address', 255);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('dealer_id')
            	->references('id')
            	->on('dealers')
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
        Schema::dropIfExists('staffs');
    }
}
