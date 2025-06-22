<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تحديث enum للحقل status لإضافة 'failed'
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'completed', 'dropped', 'failed') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // العودة للحالة السابقة
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'completed', 'dropped') DEFAULT 'active'");
    }
};
