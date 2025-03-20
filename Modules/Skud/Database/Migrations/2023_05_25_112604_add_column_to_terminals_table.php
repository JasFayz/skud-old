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
        Schema::table('terminals', function (Blueprint $table) {
            $table->foreignId('zone_id')
                ->nullable()->constrained('zones')
                ->nullOnDelete();
            $table->softDeletes();
        });
        Schema::table('terminal_zone', function (Blueprint $table) {
            $table->dropForeign('terminal_zone_terminal_id_foreign');
            $table->dropForeign('terminal_zone_zone_id_foreign');
            $table->foreign('terminal_id')
                ->references('id')
                ->on('terminals')->cascadeOnDelete();
            $table->foreign('zone_id')
                ->references('id')
                ->on('zones')
                ->cascadeOnDelete();

        });

        if (Schema::hasColumn('terminal_request_logs', 'terminal_request_logs_terminal_id_foreign')) {
            Schema::table('terminal_request_logs', function (Blueprint $table) {
                $table->dropForeign('terminal_request_logs_terminal_id_foreign');
            });
        }

        Schema::table('terminal_request_logs', function (Blueprint $table) {
            $table->softDeletes();

        });

        if (Schema::hasColumn('terminal_action_logs', 'terminal_request_logs_terminal_id_foreign')) {
            Schema::table('terminal_action_logs', function (Blueprint $table) {
                $table->dropForeign('terminal_action_logs_terminal_id_foreign');
            });
        }
        Schema::table('terminal_action_logs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('zones', function (Blueprint $table) {
            $table->softDeletes();
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
            $table->dropColumn('zone_id');
            $table->dropSoftDeletes();
        });
        Schema::table('terminal_request_logs', function (Blueprint $table) {
            $table->dropSoftDeletes();

        });
        Schema::table('terminal_action_logs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('zones', function (Blueprint $table) {
            $table->dropSoftDeletes();

        });
    }
};
