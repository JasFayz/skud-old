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
        Schema::create('oauth_logs', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('sess_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('sur_name')->nullable();
            $table->string('mid_name')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('egov_user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('pport_no')->nullable();
            $table->string('pin')->nullable();
            $table->text('payload')->nullable();
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
        Schema::dropIfExists('oauth_logs');
    }
};
