<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('guest_zones', function (Blueprint $table) {
            $table->foreignId('guest_id')
                ->constrained('guests')
                ->cascadeOnDelete();
            $table->foreignId('zone_id')
                ->constrained('zones')
                ->cascadeOnDelete();
            $table->unique(['guest_id', 'zone_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_zones');
    }
};
