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
        Schema::create('guest_terminal', function (Blueprint $table) {
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();
            $table->foreignId('terminal_id')->constrained('terminals')->cascadeOnDelete();
            $table->foreignId('invite_id')->constrained('invites')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['guest_id', 'terminal_id', 'invite_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_terminal');
    }
};
