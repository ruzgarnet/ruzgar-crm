<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_no', 31)->unique();
            $table->string('identification_number', 15)->unique();
            $table->string('first_name', 63);
            $table->string('last_name', 63);
            $table->string('telephone', 15);
            $table->string('email');
            $table->unsignedTinyInteger('type')->default(1);
            $table->string('reference_code')->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);
        });

        Schema::create('customer_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('secondary_telephone', 15)->nullable()->default(null);
            $table->unsignedTinyInteger('gender');
            $table->date('birthday');
            $table->foreignId('city_id');
            $table->foreignId('district_id');
            $table->string('address', 255);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('customer_id')
            	->references('id')
            	->on('customers')
            	->onUpdate('CASCADE')
            	->onDelete('CASCADE');

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
        Schema::dropIfExists('customers');
    }
}
