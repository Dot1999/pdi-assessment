<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Announcement extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title',
        'content',
        'author'
    ];

    public function setDepartments($department_ids)
    {
        if(!@$this->id) return;

        $id = $this->id;
        DepartmentAnnouncement::where('announcement_id', $id)->delete();
        $departmentAnnouncements = array_map(function($department_id) use ($id) {
            return [
                "announcement_id" => $id,
                "department_id" => $department_id,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ];
        }, $department_ids);
        DepartmentAnnouncement::insert($departmentAnnouncements);
    }

    public function department()
    {
        return $this->hasManyThrough(Department::class, DepartmentAnnouncement::class, "announcement_id", "id", "id", "department_id")->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, "author", "id");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'content', 'user.name'])
            ->logOnlyDirty();
    }
}
