<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_postings')->onDelete('cascade');
            $table->string('candidate_name');
            $table->string('candidate_email')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->longText('raw_text')->nullable();
            $table->enum('status', ['pending', 'screened', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
