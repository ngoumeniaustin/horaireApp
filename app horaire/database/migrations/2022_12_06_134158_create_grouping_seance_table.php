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
        Schema::create('GroupingSeance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idRegroupement',10);
            $table->foreignId("idSeance")->references("idSeance")->on("Seances")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GroupingSeance');
    }
};
