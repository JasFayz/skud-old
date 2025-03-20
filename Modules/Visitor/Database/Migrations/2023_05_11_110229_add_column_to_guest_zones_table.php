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
        Schema::table('guest_zones', function (Blueprint $table) {
            $table->foreignId('invite_id')
                ->constrained('invites')
                ->cascadeOnDelete();

            $table->dropUnique(['guest_id', 'zone_id']);
            $table->unique(['guest_id', 'zone_id', 'invite_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest_zones', function (Blueprint $table) {
            $table->dropUnique(['guest_id', 'zone_id', 'invite_id']);
            $table->unique(['guest_id', 'zone_id']);
            $table->dropColumn('invite_id');
        });
    }
};
