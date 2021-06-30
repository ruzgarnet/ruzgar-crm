<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->nullable()->default(null);
            $table->string('name');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->string('key')->unique()->nullable()->default(null);
            $table->string('name');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default(null);
        });

        Schema::create('ability_role', function (Blueprint $table) {
            $table->foreignId('role_id');
            $table->foreignId('ability_id');

            $table->primary(['role_id', 'ability_id']);

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('ability_id')
                ->references('id')
                ->on('abilities')
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
        Schema::dropIfExists('ability_role');
        Schema::dropIfExists('abilities');
        Schema::dropIfExists('roles');
    }
}
