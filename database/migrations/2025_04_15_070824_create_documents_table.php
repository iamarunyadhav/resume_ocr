<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->string('context')->default('resume'); // switchable
            $table->json('extracted_data')->nullable();  // AI-processed
            $table->json('suggestions')->nullable();     // AI suggestions
            $table->enum('status', ['draft', 'completed', 'failed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
