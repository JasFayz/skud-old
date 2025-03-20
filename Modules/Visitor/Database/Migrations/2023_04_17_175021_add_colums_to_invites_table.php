<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Visitor\Entities\Invite;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->integer('status')->default(Invite::STATUS_PENDING)->after('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');
            $table->dropColumn('status');
        });
    }
};
