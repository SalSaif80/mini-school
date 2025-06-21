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
        // Update teachers table - make phone nullable
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
        });

        // Update students table - add missing fields
        Schema::table('students', function (Blueprint $table) {
            $table->date('date_of_birth')->after('student_id');
            $table->enum('gender', ['male', 'female', 'other'])->after('date_of_birth');
            $table->text('address')->after('gender');
            $table->string('phone')->nullable()->after('address');
            $table->string('parent_name')->after('phone');
            $table->string('parent_phone')->after('parent_name');
            $table->string('academic_year')->after('parent_phone');

            // Rename existing fields to match seeder expectations
            $table->renameColumn('enrollment_date', 'enrollment_date');
            $table->renameColumn('major', 'major');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse teachers table changes
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('phone')->nullable(false)->change();
        });

        // Reverse students table changes
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'address',
                'phone',
                'parent_name',
                'parent_phone',
                'academic_year'
            ]);
        });
    }
};
