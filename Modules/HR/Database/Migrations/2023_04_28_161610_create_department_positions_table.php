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
        Schema::create('department_positions', function (Blueprint $table) {
            $table->foreignId('department_id')
                ->constrained('departments')->cascadeOnDelete();
            $table->foreignId('position_id')
                ->constrained()->cascadeOnDelete();
            $table->unique(['department_id', 'position_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_positions');
    }
};
