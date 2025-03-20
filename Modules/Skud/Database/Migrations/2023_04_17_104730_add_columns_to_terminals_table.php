<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Skud\Entities\Terminal;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terminals', function (Blueprint $table) {
            $table->integer('status')->default(Terminal::STATUS_ACTIVE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terminals', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
