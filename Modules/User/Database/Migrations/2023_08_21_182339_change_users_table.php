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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->nullOnDelete();
//            $table->string('email')->unique()->change();
//            $table->unique(['email', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
//            $table->dropUnique(['email', 'company_id']);
//            $table->string('email')->unique(false)->change();
            $table->dropForeign('users_company_id_foreign');
        });
    }
};
