<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaultRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fault_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('status')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);
        });

        Schema::create('fault_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('fault_type_id');
            $table->tinyInteger("status")->default(1);
            $table->text('description')->nullable()->default(null);
            $table->string("serial_number");
            $table->string("solution_detail");
            $table->json('files')->nullable()->default(null);
            $table->timestamp('seen_at')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('fault_type_id')
                ->references('id')
                ->on('fault_types')
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
        Schema::dropIfExists('fault_records');
        Schema::dropIfExists('fault_types');
    }
}
