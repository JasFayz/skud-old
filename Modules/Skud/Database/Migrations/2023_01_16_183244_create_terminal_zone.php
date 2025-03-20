<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('terminal_zone', function (Blueprint $table) {
            $table->foreignId('terminal_id')
                ->constrained('terminals');
            $table->foreignId('zone_id')
                ->constrained('zones');
        });
    }

    public function down()
    {
        Schema::dropIfExists('terminal_zone');
    }
};
