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
        Schema::create('terminals_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terminal_id')
                ->constrained('terminals')
                ->cascadeOnDelete();
            $table->boolean('success')->default(false);
            $table->boolean('skip')->default(false);
            $table->text('message')->nullable();

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
        Schema::dropIfExists('');
    }
};
