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
            $table->boolean('is_fired')->default(false);
            $table->timestamp('hired_at')->nullable();
        });

        Schema::create('fired_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->noActionOnDelete();
            $table->foreignId('fired_by')->constrained('users')->noActionOnDelete();
            $table->timestamp('fired_at')->nullable();
            $table->date('fired_date');
            $table->integer('status');
            $table->integer('has_terminals');
            $table->softDeletes();
            $table->unique(['user_id', 'fired_date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('users', 'is_fired');
        Schema::dropColumns('users', 'hired_at');
        Schema::dropIfExists('fired_user');
    }
};
