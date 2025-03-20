<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Skud\Entities\TerminalMode;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('floor_id')
                ->nullable()->constrained('floors')->nullOnDelete();
            $table->string('ip');
            $table->string('port');
            $table->string('token');
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();
            $table->boolean('mode');
            $table->string('serial_number');
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
        Schema::dropIfExists('terminals');
    }
};
