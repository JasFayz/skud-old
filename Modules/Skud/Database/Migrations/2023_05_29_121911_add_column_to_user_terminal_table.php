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
        Schema::table('user_terminal', function (Blueprint $table) {
            $table->unique(['user_id', 'terminal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_terminal', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'terminal_id']);
        });
    }
};
