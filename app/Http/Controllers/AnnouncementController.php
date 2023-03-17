<?php

namespace App\Http\Controllers;

use App\Exports\AnnouncementExport;
use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!auth()->user()->canAccess('announcement-view'), 403, "Permission Denied");
        $search_query = request('search');
        $query = Announcement::query();
        if(@$search_query) {
            $query->where('title', 'like', '%' . $search_query . '%')
                  ->orWhereHas('department', function($query) use ($search_query) {
                        $query->where('name', 'like', '%' . $search_query . '%'); })
                  ->orWhereHas('user', function($query) use ($search_query) {
                            $query->where('name', 'like', '%' . $search_query . '%');
                  });
        }
        return view('announcement.index', ['announcements' => $query->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->canAccess('announcement-create'), 403, "Permission Denied");
        return view('announcement.store', [
            'departments' => Department::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->canAccess('announcement-create'), 403, "Permission Denied");
        $validated = $request->validate([
            "title" => ["required", "max:255"],
            "content" => ["required"],
            "department_id" => ["required"],
        ]);

        $announcement = Announcement::create([
            "title" => $request->title,
            "content" => $request->content,
            "author" => auth()->user()->id
        ]);

        $announcement->setDepartments($request->department_id);
        return redirect()->route('announcement.index')->with("success", "Added successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        abort_if(!auth()->user()->canAccess('announcement-update'), 403, "Permission Denied");
        return view('announcement.update', [
            'announcement' => $announcement,
            'departments' => Department::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        abort_if(!auth()->user()->canAccess('announcement-update'), 403, "Permission Denied");
        $validated = $request->validate([
            "title" => ["required", "max:255"],
            "content" => ["required"],
            "department_id" => ["required"],
        ]);

        $announcement->title = $request->title;
        $announcement->content = $request->content;
        $announcement->update();

        $announcement->setDepartments($request->department_id);
        return redirect()->back()->with("success", "Updated successfully");
    }

    public function deleteModal($id)
    {
        abort_if(!auth()->user()->canAccess('announcement-delete'), 403, "Permission Denied");
        return view('announcement.delete', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        abort_if(!auth()->user()->canAccess('announcement-delete'), 403, "Permission Denied");
        $announcement->delete();
        return redirect()->back()->with('success', 'Deleted successfully');
    }

    public function export(Request $request)
    {
        abort_if(!auth()->user()->canAccess('announcement-export'), 403, "Permission Denied");
        if ($request->format == "CSV") {
            return (new AnnouncementExport($request->search_query))->download('announcement.csv', \Maatwebsite\Excel\Excel::CSV);
        } else if ($request->format == "XLSX") {
            return (new AnnouncementExport($request->search_query))->download('announcement.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else if ($request->format == "PDF") {
            return (new AnnouncementExport($request->search_query))->download('announcement.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        } else if ($request->format == "TXT") {
            return (new AnnouncementExport($request->search_query))->download('announcement.txt', \Maatwebsite\Excel\Excel::CSV);
        } else {
            return redirect()->back()->withErrors(["error" => "No file format selected"]);
        }
    }

    public function print()
    {
        abort_if(!auth()->user()->canAccess('announcement-view'), 403, "Permission Denied");
        $search_query = request('search');
        $query = Announcement::query();
        if(@$search_query) {
            $query->where('title', 'like', '%' . $search_query . '%')
                  ->orWhereHas('department', function($query) use ($search_query) {
                        $query->where('name', 'like', '%' . $search_query . '%'); })
                  ->orWhereHas('user', function($query) use ($search_query) {
                            $query->where('name', 'like', '%' . $search_query . '%');
                  });
        }
        return view('announcement.print', ['announcements' => $query->get()]);
    }
}
