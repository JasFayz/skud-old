<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_terminal', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('terminal_id')
                ->constrained('terminals')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_terminal');
    }
};
