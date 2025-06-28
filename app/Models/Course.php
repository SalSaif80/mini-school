<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['course_name', 'teacher_id', 'schedule_date', 'room_number'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $userName = Auth::user()?->name ?? 'مستخدم غير معروف';

        switch ($eventName) {
            case 'created':
                $activity->description = "تم إنشاء كورس جديد بواسطة: {$userName}";
                break;

            case 'updated':
                $activity->description = "تم تعديل بيانات الكورس بواسطة: {$userName}";
                break;

            case 'deleted':
                $activity->description = "تم حذف الكورس بواسطة: {$userName}";
                break;
        }

        $activity->properties = $activity->properties->put('ip_address', request()->ip());
        $activity->properties = $activity->properties->put('user_agent', request()->userAgent());
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
