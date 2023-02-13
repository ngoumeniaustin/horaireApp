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
        //
        Schema::create("seances", function (Blueprint $table) {
            $table->bigIncrements('idSeance')->length(5);
            if (DB::getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME) == 'mysql') {
                $table->time('heureDebut')->format("hh:mm");
                $table->time('heureFin')->format("hh:mm");
                $table->time('duree')->format("hh:mm");
                $table->date('date')->format("yyyy-mm-dd hh:mm:ss");
            } else {
                $table->date('heureDebut')->format("hh:mm");
                $table->date('heureFin')->format("hh:mm");
                $table->date('duree')->format("hh:mm");
                $table->date('date')->format("yyyy-mm-dd");
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seances');
    }
};
