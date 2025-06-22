<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    const ADMIN = 'admin';
    const TEACHER = 'teacher';
    const STUDENT = 'student';

    public static function availableRoles(): array
    {
        return [
            self::ADMIN,
            self::TEACHER,
            self::STUDENT,
        ];
    }

     public static function roleLabels(): array
     {
         return [
             self::ADMIN => 'مدير',
             self::TEACHER => 'معلم',
             self::STUDENT => 'طالب',
         ];
     }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Activity Log Configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'username', 'user_type'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->user_type === self::ADMIN;
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->user_type === self::TEACHER;
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->user_type === self::STUDENT;
    }

    /**
     * Student profile relationship
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Teacher profile relationship
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Teacher courses relationship
     */
    public function teacherCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * Student enrollments relationship
     */
    public function studentEnrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * Enrollments relationship (alias for studentEnrollments)
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }
}
