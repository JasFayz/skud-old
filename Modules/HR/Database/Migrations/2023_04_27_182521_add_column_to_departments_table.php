<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->nestedSet();
        });

        Schema::table('divisions', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->change();
        });
        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('division_id')->nullable()->change();
            $table->foreignId('company_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {

            $table->dropNestedSet();
        });
    }
};
