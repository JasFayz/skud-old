<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('terminal_request_logs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->dateTime('terminal_date');
            $table->foreignId('terminal_id')->constrained('terminals');
            $table->uuid('identifier_number')->index()->nullable();
            $table->text('photo')->nullable();
            $table->boolean('terminal_mode')->nullable();
            $table->boolean('is_calc_attend')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('terminal_request_logs');
    }
};
