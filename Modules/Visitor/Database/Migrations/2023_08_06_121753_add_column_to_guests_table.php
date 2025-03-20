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
        Schema::table('guests', function (Blueprint $table) {
            $table->string('patronymic')->nullable();
            $table->string('passport_number')->nullable();
            $table->boolean('is_vip')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('patronymic');
            $table->dropColumn('passport_number');
            $table->dropColumn('is_vip');
        });
    }
};
