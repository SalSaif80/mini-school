<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'title',
        'description',
        'credit_hours',
        'teacher_id',
        'level',
    ];

    /**
     * Teacher relationship
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Enrollments relationship
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Students relationship through enrollments
     */
    public function students()
    {
        return $this->hasManyThrough(Student::class, Enrollment::class);
    }

    /**
     * Active enrollments
     */
    public function activeEnrollments()
    {
        return $this->enrollments()->where('status', 'active');
    }

    /**
     * Completed enrollments
     */
    public function completedEnrollments()
    {
        return $this->enrollments()->where('status', 'completed');
    }
}
