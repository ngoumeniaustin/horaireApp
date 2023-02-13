<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grouping', function (Blueprint $table) {
            $table->string('idRegroupement',10);
            $table->string('idGroup',5);
            $table->foreign('idGroup')->references('idGroupe')->on('groups');
            $table->primary(array('idRegroupement','idGroup'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grouping');
    }
};
