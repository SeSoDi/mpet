<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LogController extends Controller
{
    /**
     * Display a paginated list of logs with filtering
     */
    public function index(Request $request): Response
    {
        $query = Log::with('user:id,name,email')
            ->orderBy('logged_at', 'desc');

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by channel
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('logged_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('logged_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Search in message
        if ($request->filled('search')) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(50)->appends($request->query());

        // Get unique values for filters
        $filters = [
            'levels' => Log::select('level')->distinct()->pluck('level')->sort()->values(),
            'channels' => Log::select('channel')->distinct()->pluck('channel')->sort()->values(),
        ];

        return Inertia::render('Logs/Index', [
            'logs' => $logs,
            'filters' => $filters,
            'queryParams' => $request->query(),
        ]);
    }

    /**
     * Show a specific log entry with full details
     */
    public function show(Log $log): Response
    {
        $log->load('user:id,name,email');
        
        return Inertia::render('Logs/Show', [
            'log' => $log,
        ]);
    }

    /**
     * Delete a log entry (for cleanup purposes)
     */
    public function destroy(Log $log)
    {
        $log->delete();

        return back()->with('success', 'Log eliminado correctamente.');
    }

    /**
     * Bulk delete logs based on criteria
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'older_than_days' => 'required|integer|min:1',
            'level' => 'nullable|string',
            'channel' => 'nullable|string',
        ]);

        $query = Log::where('logged_at', '<', now()->subDays($request->older_than_days));

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        $deletedCount = $query->delete();

        return back()->with('success', "Se eliminaron {$deletedCount} logs correctamente.");
    }

    /**
     * Get log statistics for dashboard
     */
    public function stats()
    {
        $stats = [
            'total' => Log::count(),
            'today' => Log::whereDate('logged_at', today())->count(),
            'this_week' => Log::where('logged_at', '>=', now()->startOfWeek())->count(),
            'by_level' => Log::selectRaw('level, COUNT(*) as count')
                ->groupBy('level')
                ->pluck('count', 'level'),
            'by_channel' => Log::selectRaw('channel, COUNT(*) as count')
                ->groupBy('channel')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'channel'),
            'recent_errors' => Log::where('level', 'ERROR')
                ->orWhere('level', 'CRITICAL')
                ->orderBy('logged_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        return response()->json($stats);
    }
}