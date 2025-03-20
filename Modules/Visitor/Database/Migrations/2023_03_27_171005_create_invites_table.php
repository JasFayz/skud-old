<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Visitor\Entities\Invite;

return new class extends Migration {
    public function up()
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start');
            $table->timestamp('end');
            $table->foreignId('responsible_user_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('target_user_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->text('comment')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();
            $table->foreignId('guest_id')
                ->constrained('guests')
                ->nullOnDelete();
            $table->boolean('is_editable')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invites');
    }
};
