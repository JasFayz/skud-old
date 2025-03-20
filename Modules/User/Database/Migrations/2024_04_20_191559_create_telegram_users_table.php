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
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('telegram_id');
            $table->string('username');
            $table->string('hash');
            $table->string('auth_date');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('photo_url')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('telegram_users');
    }
};
