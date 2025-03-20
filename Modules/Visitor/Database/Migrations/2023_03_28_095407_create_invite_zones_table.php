<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invite_zones', function (Blueprint $table) {
            $table->foreignId('invite_id')
                ->constrained('invites')
                ->cascadeOnDelete();
            $table->foreignId('zone_id')
                ->constrained('zones')
                ->cascadeOnDelete();
            $table->unique(['invite_id', 'zone_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invite_zones');
    }
};
