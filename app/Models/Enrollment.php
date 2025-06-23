<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Enrollment extends Model
{
    use LogsActivity;

    protected $primaryKey = 'enrollment_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'course_id',
        'enrollment_date',
        'completion_date',
        'semester',
        'grade',
        'status',
        'final_exam_grade',
        'exam_file_path',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
            'completion_date' => 'date',
        ];
    }

    // Activity Log Configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['student_id', 'course_id', 'enrollment_date', 'completion_date', 'semester', 'grade', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Status Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DROPPED = 'dropped';
    const STATUS_FAILED = 'failed';

    // Grade Constants
    const GRADES = ['A+', 'A', 'B+', 'B', 'C+', 'C', 'D+', 'D', 'F'];

    /**
     * Get the student that belongs to this enrollment
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the course that belongs to this enrollment
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Check if enrollment is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if enrollment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if enrollment is dropped
     */
    public function isDropped(): bool
    {
        return $this->status === self::STATUS_DROPPED;
    }

    /**
     * Check if enrollment is failed
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Mark enrollment as completed
     */
    public function markCompleted($grade = null): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completion_date' => now(),
            'grade' => $grade,
        ]);
    }

    /**
     * Mark enrollment as dropped
     */
    public function markDropped(): void
    {
        $this->update([
            'status' => self::STATUS_DROPPED,
        ]);
    }

    /**
     * Scope for active enrollments
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for completed enrollments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for dropped enrollments
     */
    public function scopeDropped($query)
    {
        return $query->where('status', self::STATUS_DROPPED);
    }

    /**
     * Scope for failed enrollments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Calculate letter grade from numeric grade
     */
    public function calculateLetterGrade($numericGrade): string
    {
        if ($numericGrade >= 95) return 'A+';
        if ($numericGrade >= 90) return 'A';
        if ($numericGrade >= 85) return 'B+';
        if ($numericGrade >= 80) return 'B';
        if ($numericGrade >= 75) return 'C+';
        if ($numericGrade >= 70) return 'C';
        if ($numericGrade >= 65) return 'D+';
        if ($numericGrade >= 60) return 'D';
        if ($numericGrade >= 50) return 'D-';
        return 'F';
    }

    public function updateGradeAndStatus(): void
    {
        if ($this->final_exam_grade !== null) {
            $letterGrade = $this->calculateLetterGrade($this->final_exam_grade);
            $status = $this->final_exam_grade >= 50 ? self::STATUS_COMPLETED : self::STATUS_FAILED;

            $this->update([
                'grade' => $letterGrade,
                'status' => $status,
                'completion_date' => $status === self::STATUS_COMPLETED ? now() : null,
            ]);
        }
    }

    /**
     * Check if student passed
     */
    public function hasPassed(): bool
    {
        return $this->final_exam_grade >= 50;
    }

    /**
     * Get grade color class for display
     */
    public function getGradeColorClass(): string
    {
        if (!$this->grade) return 'bg-secondary';

        return match($this->grade) {
            'A+', 'A' => 'bg-success',
            'B+', 'B' => 'bg-info',
            'C+', 'C' => 'bg-primary',
            'D+', 'D', 'D-' => 'bg-warning',
            'F' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Get status color class for display
     */
    public function getStatusColorClass(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'bg-primary',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_FAILED => 'bg-danger',
            self::STATUS_DROPPED => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayName(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_FAILED => 'راسب',
            self::STATUS_DROPPED => 'منسحب',
            default => 'غير محدد'
        };
    }
}
