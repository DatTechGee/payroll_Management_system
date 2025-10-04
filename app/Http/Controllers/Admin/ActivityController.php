<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['user'])
            ->latest();

        // Filter by module if provided
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter by activity type if provided
        if ($request->filled('type')) {
            $query->where('activity_type', $request->type);
        }

        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $activities = $query->paginate(20);

        // Get unique modules and activity types for filters
        $modules = Activity::distinct('module')->pluck('module');
        $types = Activity::distinct('activity_type')->pluck('activity_type');

        return view('admin.activities.index', compact('activities', 'modules', 'types'));
    }
}