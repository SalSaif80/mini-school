<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'date_of_birth',
        'gender',
        'address',
        'phone',
        'hire_date',
        'department',
        'specialization',
        'salary',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Courses relationship
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Students through courses and enrollments
     */
    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            Course::class,
            'teacher_id',
            'id',
            'id',
            'id'
        )->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
         ->join('students', 'enrollments.student_id', '=', 'students.id');
    }
}
