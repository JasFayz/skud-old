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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('patronymic')->nullable();
            $table->string('photo')->nullable();
            $table->date('birthDay')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('car_number')->nullable();
            $table->foreignId('department_id')->nullable();
            $table->foreignId('division_id')->nullable();
            $table->foreignId('position_id')->nullable();
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();
            $table->foreignId('user_id')
                ->nullable()->constrained('users')
                ->cascadeOnDelete();
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
        Schema::dropIfExists('profiles');
    }
};
