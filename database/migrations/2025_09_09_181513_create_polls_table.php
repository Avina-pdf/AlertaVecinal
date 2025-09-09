<?php

// database/migrations/2025_01_01_000000_create_polls_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // creador
            $table->string('title', 160);
            $table->text('description')->nullable();
            $table->timestamp('closes_at')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('polls');
    }
};
