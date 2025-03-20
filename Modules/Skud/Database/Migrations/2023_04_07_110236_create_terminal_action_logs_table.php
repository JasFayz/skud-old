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
        Schema::create('terminal_action_logs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('action_type');
            $table->foreignId('terminal_id')
                ->constrained('terminals');
            $table->uuid('identifier_number')->nullable();
            $table->string('status')
                ->nullable();
            $table->text('payload')
                ->nullable();
            $table->text('response')
                ->nullable();
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
        Schema::dropIfExists('terminal_action_logs');
    }
};
