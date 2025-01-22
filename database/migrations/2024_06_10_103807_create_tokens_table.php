<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('api_service_id')->constrained('api_services')->cascadeOnDelete();
            $table->foreignId('token_type_id')->constrained('token_types')->cascadeOnDelete();
            $table->string('token');
            $table->string('login')->nullable();    // Для токенов типа "логин и пароль"
            $table->string('password')->nullable(); // Для токенов типа "логин и пароль"
            $table->timestamp('expires_at')->nullable(); // Срок действия токена
            $table->timestamps();
    
            $table->unique(['account_id', 'api_service_id', 'token_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
