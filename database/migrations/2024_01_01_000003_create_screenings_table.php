<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('score')->default(0);
            $table->text('summary');
            $table->json('strengths');
            $table->json('weaknesses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
