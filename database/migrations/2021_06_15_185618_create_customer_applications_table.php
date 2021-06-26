<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerApplicationsTable extends Migration
{
    // TODO Başvuru tipleri eklenebilsin
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_application_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('status')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);
        });

        Schema::create('customer_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId("staff_id")->nullable()->default(null);;
            $table->foreignId("customer_application_type_id");
            $table->foreignId("customer_id")->nullable()->default(null);
            $table->tinyInteger("status")->default(1);
            $table->text("description");
            $table->json("information");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);

            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('customer_application_type_id')
                ->references('id')
                ->on('customer_application_types')
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
        Schema::dropIfExists('customer_application_types');
        Schema::dropIfExists('system_requests');
    }
}
