<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('annonceurs', function (Blueprint $table) {
            $table->boolean('deleted')->default(false);
        });

        Schema::table('bases', function (Blueprint $table) {
            $table->boolean('deleted')->default(false);
        });

        Schema::table('campagnes', function (Blueprint $table) {
            $table->boolean('deleted')->default(false);
        });

        Schema::table('plannings', function (Blueprint $table) {
            $table->boolean('deleted')->default(false);
        });

        Schema::table('routeurs', function (Blueprint $table) {
            $table->boolean('deleted')->default(false);
        });

        Schema::table('resultats', function (Blueprint $table) {
            $table->boolean('deleted')->default(false);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
