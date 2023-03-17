<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dashboard()
    {
        $dept_id = auth()->user()->department_id;
        $query = Announcement::query();
        if(auth()->user()->role_id == 2) {
            $query = $query->whereHas('department', function($query) use ($dept_id) {
                $query->where('departments.id', $dept_id);
            });
        }
        return view('dashboard', [
            "employees" => User::where("role_id", 2)->count(),
            "departments" => Department::count(),
            "announcements" => $query->orderBy("id", "desc")->paginate(5)
        ]);
    }
}
