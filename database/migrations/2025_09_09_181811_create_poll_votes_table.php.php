<?php
// database/migrations/2025_01_01_000002_create_poll_votes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_option_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // 1 voto por usuario por encuesta
            $table->unique(['poll_id','user_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('poll_votes');
    }
};
