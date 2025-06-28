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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id('enrollment_id');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->date('enrollment_date');
            $table->date('completion_date')->nullable();
            $table->string('semester');
            $table->enum('grade', ['A+', 'A', 'B+', 'B', 'C+', 'C', 'D+', 'D', 'F'])->nullable();
            $table->enum('status', ['active', 'completed', 'dropped', 'failed'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
