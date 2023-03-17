<?php

namespace App\Http\Controllers;

use App\Exports\ActivityLogExport;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->canAccess('logs-view'), 403, "Permission Denied.");
        $search_query = request('search');
        $query = Activity::query();
        $query = $query->with('causer')->orderBy('id', 'desc');
        if (@$search_query) {
            $query = $query->where('event', 'like', '%' . $search_query . '%')
                ->orWhereHas('causer', function ($query) use ($search_query) {
                    $query->where('name', 'like', '%' . $search_query . '%');
                });
        }
        return view('log.index', ['logs' => $query->paginate(10)]);
    }
    public function print()
    {
        abort_if(!auth()->user()->canAccess('logs-export'), 403, "Permission Denied.");
        $search_query = request('search');
        $query = Activity::query();
        $query = $query->with('causer');
        if (@$search_query) {
            $query = $query->where('event', 'like', '%' . $search_query . '%')
                ->orWhereHas('causer', function ($query) use ($search_query) {
                    $query->where('name', 'like', '%' . $search_query . '%');
                });
        }
        return view('log.print', ['logs' => $query->get()]);
    }
    public function export(Request $request)
    {
        abort_if(!auth()->user()->canAccess('logs-export'), 403, "Permission Denied.");
        if ($request->format == "CSV") {
            return (new ActivityLogExport($request->search_query))->download('activity_log.csv', \Maatwebsite\Excel\Excel::CSV);
        } else if ($request->format == "TXT") {
            return (new ActivityLogExport($request->search_query))->download('activity_log.txt', \Maatwebsite\Excel\Excel::CSV);
        } else if ($request->format == "XLSX") {
            return (new ActivityLogExport($request->search_query))->download('activity_log.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else if ($request->format == "PDF") {
            return (new ActivityLogExport($request->search_query))->download('activity_log.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        } else {
            return redirect()->back()->withErrors(["error" => "No file format selected"]);
        }
    }
}