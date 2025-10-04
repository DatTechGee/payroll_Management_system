<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'module',
        'description',
        'details',
        'subject_type',
        'subject_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function getChangesAttribute()
    {
        if (!$this->details) {
            return [];
        }
        
        $details = is_string($this->details) ? json_decode($this->details, true) : $this->details;
        return $details ?? [];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function getActivityColor()
    {
        $activityType = strtolower($this->activity_type ?? '');
        $description = strtolower($this->description ?? '');

        switch(true) {
            case str_contains($activityType, 'create') || str_contains($description, 'created'):
                return 'emerald';
            case str_contains($activityType, 'update') || str_contains($description, 'updated'):
                return 'blue';
            case str_contains($activityType, 'delete') || str_contains($description, 'deleted'):
                return 'red';
            case str_contains($activityType, 'login'):
                return 'violet';
            case str_contains($activityType, 'logout'):
                return 'amber';
            case str_contains($activityType, 'payroll') || str_contains($description, 'payroll'):
                return 'indigo';
            default:
                return 'sky';
        }
    }

    public function getActivityIcon()
    {
        $activityType = strtolower($this->activity_type ?? '');
        $description = strtolower($this->description ?? '');

        switch(true) {
            case str_contains($activityType, 'create') || str_contains($description, 'created'):
                return '<path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round"/>';
            case str_contains($activityType, 'update') || str_contains($description, 'updated'):
                return '<path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" stroke-linecap="round" stroke-linejoin="round"/>';
            case str_contains($activityType, 'delete') || str_contains($description, 'deleted'):
                return '<path d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" stroke-linecap="round" stroke-linejoin="round"/>';
            case str_contains($activityType, 'login'):
                return '<path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" stroke-linecap="round" stroke-linejoin="round"/>';
            case str_contains($activityType, 'logout'):
                return '<path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" stroke-linecap="round" stroke-linejoin="round"/>';
            case str_contains($activityType, 'payroll') || str_contains($description, 'payroll'):
                return '<path d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" stroke-linecap="round" stroke-linejoin="round"/>';
            default:
                return '<path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>';
        }
    }
}
