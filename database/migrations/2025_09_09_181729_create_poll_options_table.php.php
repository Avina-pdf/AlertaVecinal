<?php

// database/migrations/2025_01_01_000001_create_poll_options_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('text', 160);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('poll_options');
    }
};
