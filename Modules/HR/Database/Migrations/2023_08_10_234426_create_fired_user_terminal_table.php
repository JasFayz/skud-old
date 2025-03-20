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
        Schema::create('fired_user_terminal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fired_user_id')->constrained('fired_user')->noActionOnDelete();
            $table->foreignId('terminal_id')->constrained('terminals')->noActionOnDelete();
            $table->string('terminal_name')->nullable();
            $table->integer('status');
            $table->boolean('action_status')->nullable();
            $table->text('message')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('fired_user_terminal');
    }
};
