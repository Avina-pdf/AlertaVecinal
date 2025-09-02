<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->string('image_path')->nullable(); // storage/app/public/posts/...
            $table->timestamps();
            $table->index(['user_id','created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('posts'); }
};
