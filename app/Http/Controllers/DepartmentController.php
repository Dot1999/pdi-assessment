<?php

namespace App\Http\Controllers;

use App\Exports\DepartmentExport;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!auth()->user()->canAccess('department-view'), 403, "Permission Denied");
        $search_query = request('search');
        $query = Department::query();
        if(@$search_query) {
            $query = $query->where('name', 'like', '%' . $search_query . '%');
        }
        return view('department.index', ['departments' => $query->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->canAccess('department-create'), 403, "Permission Denied");
        return view('department.store');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->canAccess('department-create'), 403, "Permission Denied");
        $validated = $request->validate([
            'department_name' => ['required', 'max:100'],
        ]);

        Department::create(["name" => $request->department_name]);
        return redirect()->back()->with('success', 'Added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        abort_if(!auth()->user()->canAccess('department-update'), 403, "Permission Denied");
        return view('department.update', ['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        abort_if(!auth()->user()->canAccess('department-update'), 403, "Permission Denied");
        $request->validate([
            'department_name' => ['required', 'max:100'],
        ]);

        $department->name = $request->department_name;
        $department->update();

        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function deleteModal($id)
    {
        abort_if(!auth()->user()->canAccess('department-delete'), 403, "Permission Denied");
        return view('department.delete', ['id' => $id]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        abort_if(!auth()->user()->canAccess('department-delete'), 403, "Permission Denied");
        $department->delete();
        return redirect()->back()->with('success', 'Deleted successfully');
    }

    public function export(Request $request)
    {
        abort_if(!auth()->user()->canAccess('department-export'), 403, "Permission Denied");
        if ($request->format == "CSV") {
            return (new DepartmentExport($request->search_query))->download('departments.csv', \Maatwebsite\Excel\Excel::CSV);
        } else if ($request->format == "XLSX") {
            return (new DepartmentExport($request->search_query))->download('departments.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else if ($request->format == "PDF") {
            return (new DepartmentExport($request->search_query))->download('departments.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        } else if ($request->format == "TXT") {
            return (new DepartmentExport($request->search_query))->download('departments.txt', \Maatwebsite\Excel\Excel::CSV);
        } else {
            return redirect()->back()->withErrors(["error" => "No file format selected"]);
        }
    }

    public function print()
    {
        abort_if(!auth()->user()->canAccess('department-view'), 403, "Permission Denied");
        $search_query = request('search');
        $query = Department::query();
        if(@$search_query) {
            $query = $query->where('name', 'like', '%' . $search_query . '%');
        }
        return view('department.print', ['departments' => $query->get()]);
    }
}
