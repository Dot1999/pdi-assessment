<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\Department;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!auth()->user()->canAccess('user-view'), 403, "Permission Denied");
        $search_query = request('search');
        $query = User::query();
        $query = $query->where('role_id', '!=', 1);
        if(@$search_query) {
            $query = $query->where(function($query) use ($search_query) {
                $query->where('name', 'like', '%' . $search_query . '%')
                           ->orWhere('email', 'like', '%' . $search_query . '%')
                           ->orWhereHas('department', function($query) use ($search_query) {
                            $query->where('name', 'like', '%' . $search_query . '%');
                });
            });
        }
        return view('user.index', ['users' => $query->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->canAccess('user-create'), 403, "Permission Denied");
        return view('user.store', [
            'permissions' => Permission::formattedPermissions(),
            'departments' => Department::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->canAccess('user-create'), 403, "Permission Denied");
        $validated = $request->validate([
            "name" => ["required", "max:100"],
            "email" => ["email", "unique:users", "max:100"],
            "department_id" => ["required", "numeric", "max:255"],
            "permissions" => ["required"],
            "password" => ["required", "confirmed", Password::min(8)]
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "department_id" => $request->department_id,
            "password" => Hash::make($request->password),
            "role_id" => 2
        ]);
        $user->setPermissions($request->permissions);
        return redirect()->route('user.index')->with("success", "Added successfully");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        abort_if(!auth()->user()->canAccess('user-update'), 403, "Permission Denied");
        return view('user.update', [
            'user' => $user,
            'permissions' => Permission::formattedPermissions(),
            'departments' => Department::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        abort_if(!auth()->user()->canAccess('user-update'), 403, "Permission Denied");
        $validated = $request->validate([
            "name" => ["required", "max:100"],
            "email" => ["email", "unique:users,email," . $user->id, "max:100"],
            "department_id" => ["required", "numeric"],
            "permissions" => ["required"],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = $request->department_id;
        $user->update();

        $user->setPermissions($request->permissions);

        return redirect()->back()->with("success", "Updated successfully");
    }

    public function deleteModal($id)
    {
        abort_if(!auth()->user()->canAccess('user-delete'), 403, "Permission Denied");
        return view('user.delete', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        abort_if(!auth()->user()->canAccess('user-delete'), 403, "Permission Denied");
        $user->delete();
        return redirect()->back()->with('success', 'Deleted successfully');
    }
    
    public function export(Request $request)
    {
        abort_if(!auth()->user()->canAccess('user-export'), 403, "Permission Denied");
        if ($request->format == "CSV") {
            return (new UserExport($request->search_query))->download('employees.csv', \Maatwebsite\Excel\Excel::CSV);
        } else if ($request->format == "XLSX") {
            return (new UserExport($request->search_query))->download('employees.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else if ($request->format == "PDF") {
            return (new UserExport($request->search_query))->download('employees.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        } else if ($request->format == "TXT") {
            return (new UserExport($request->search_query))->download('employees.txt', \Maatwebsite\Excel\Excel::CSV);
        } else {
            return redirect()->back()->withErrors(["error" => "No file format selected"]);
        }
    }

    public function show(User $user)
    {
        abort_if(!auth()->user()->canAccess('user-view'), 403, "Permission Denied");
        return view('user.permission', [
            'user' => $user
        ]);
    }

    public function print()
    {
        abort_if(!auth()->user()->canAccess('user-view'), 403, "Permission Denied");
        $search_query = request('search');
        $query = User::query();
        $query = $query->where('role_id', '!=', 1);
        if(@$search_query) {
            $query = $query->where(function($query) use ($search_query) {
                $query->where('name', 'like', '%' . $search_query . '%')
                           ->orWhere('email', 'like', '%' . $search_query . '%')
                           ->orWhereHas('department', function($query) use ($search_query) {
                            $query->where('name', 'like', '%' . $search_query . '%');
                });
            });
        }
        return view('user.print', ['users' => $query->get()]);
    }
}
