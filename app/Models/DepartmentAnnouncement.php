<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DepartmentAnnouncement extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'department_id',
        'announcement_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['announcement.id', 'announcement.title', 'department.name'])
            ->logOnlyDirty();
    }
}
