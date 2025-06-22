<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Course extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'course_id';

    protected $fillable = [
        'course_name',
        'teacher_id',
        'schedule_date',
        'room_number',
    ];

    protected function casts(): array
    {
        return [
            'schedule_date' => 'datetime',
        ];
    }

    // Activity Log Configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['course_name', 'teacher_id', 'schedule_date', 'room_number'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Teacher relationship
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Enrollments relationship
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    /**
     * Students relationship through enrollments
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id');
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
