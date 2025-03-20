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
        Schema::create('invite_terminal', function (Blueprint $table) {
            $table->foreignId('invite_id')->constrained('invites')->cascadeOnDelete();
            $table->foreignId('terminal_id')->constrained('terminals')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invite_terminal');
    }
};
