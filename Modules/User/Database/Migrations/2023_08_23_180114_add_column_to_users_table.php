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
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->noActionOnDelete();
            $table->foreignId('edited_by')
                ->nullable()
                ->constrained('users')
                ->noActionOnDelete();
            $table->string('created_by_name')->nullable();
            $table->string('edited_by_name')->nullable();
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
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('edited_by');
            $table->dropColumn('created_by_name');
            $table->dropColumn('edited_by_name');
        });
    }
};
