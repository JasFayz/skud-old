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
        Schema::create('guest_deleted_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invite_id')
                ->constrained('invites')
                ->cascadeOnDelete();
            $table->foreignId('terminal_id')
                ->constrained('terminals')
                ->cascadeOnDelete();
            $table->foreignId('guest_id')
                ->constrained('guests')
                ->cascadeOnDelete();
            $table->boolean('status');
            $table->string('message');
            $table->string('payload');
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
        Schema::dropIfExists('guest_deleted_logs');
    }
};
