<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'course_id',
        'enrollment_date',
        'status',
        'grade',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'enrollment_date' => 'date',
        'grade' => 'decimal:2',
    ];

    /**
     * Get the student that belongs to this enrollment
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course that belongs to this enrollment
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if enrollment is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if enrollment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if enrollment is dropped
     */
    public function isDropped(): bool
    {
        return $this->status === 'dropped';
    }

    /**
     * Mark enrollment as completed
     */
    public function markAsCompleted($grade = null)
    {
        $this->update([
            'status' => 'completed',
            'grade' => $grade,
        ]);
    }

    /**
     * Mark enrollment as dropped
     */
    public function markAsDropped()
    {
        $this->update([
            'status' => 'dropped',
        ]);
    }

    /**
     * Scope for active enrollments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed enrollments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for dropped enrollments
     */
    public function scopeDropped($query)
    {
        return $query->where('status', 'dropped');
    }
}
