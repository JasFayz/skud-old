<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('terminal_user_identifiers', function (Blueprint $table) {
            $table->id();
            $table->uuid('identifier_number')->unique()->index();
            $table->integer('model_id');
            $table->string('model_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('terminal_identifier');
    }
};
