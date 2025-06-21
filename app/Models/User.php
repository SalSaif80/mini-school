<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'date_of_birth',
        'gender',
        'address',
        'phone',
        'hire_date',
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

    const TEACHER_ROLE = 'teacher';
    const STUDENT_ROLE = 'student';
    const ADMIN_ROLE = 'admin';
    const SUPER_ADMIN_ROLE = 'super_admin';

    public static function availableRoles(): array
    {
        return [
            self::ADMIN_ROLE,
            self::TEACHER_ROLE,
            self::STUDENT_ROLE,
            self::SUPER_ADMIN_ROLE,
        ];
    }

     public static function roleLabels(): array
     {
         return [
             self::ADMIN_ROLE => 'مدير',
             self::TEACHER_ROLE => 'معلم',
             self::STUDENT_ROLE => 'طالب',
             self::SUPER_ADMIN_ROLE => 'مدير النظام',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ADMIN_ROLE;
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === self::TEACHER_ROLE;
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === self::STUDENT_ROLE;
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

}
