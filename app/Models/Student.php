<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'date_of_birth',
        'gender',
        'address',
        'phone',
        'parent_name',
        'parent_phone',
        'academic_year',
        'enrollment_date',
        'major',
        'class_level',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Enrollments relationship
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Courses relationship through enrollments
     */
    public function courses()
    {
        return $this->hasManyThrough(Course::class, Enrollment::class);
    }

}
