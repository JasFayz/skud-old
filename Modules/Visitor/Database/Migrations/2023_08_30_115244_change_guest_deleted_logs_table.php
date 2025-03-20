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
        Schema::table('guest_deleted_logs', function (Blueprint $table) {
            $table->text('message')->change();
            $table->text('payload')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest_deleted_logs', function (Blueprint $table) {
            $table->text('message')->change();
            $table->text('payload')->change();
        });
    }
};
