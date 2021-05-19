<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id');
            $table->string('identification_number', 15)->unique();
            $table->string('first_name', 63);
            $table->string('last_name', 63);
            $table->unsignedTinyInteger('gender');
            $table->string('telephone', 15);
            $table->string('email', 31);
            $table->string('secondary_telephone', 15)->nullable()->default(null);
            $table->date('birthday');
            $table->string('address', 255);
            $table->date('started_at')->useCurrent();
            $table->date('released_at')->nullable()->default(null);
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
