<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->date('date');
            $table->foreignId('terminal_id')
                ->constrained('terminals')
                ->nullOnDelete();
            $table->time('came_time')->nullable();
            $table->time('left_time')->nullable();
            $table->integer('time_in')->nullable();
            $table->integer('time_out')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_attendances');
    }
};
