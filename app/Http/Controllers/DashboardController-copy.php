<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $data = Cache::remember('dashboard_stats', 60, function () {
            return [
                'total' => Ticket::count(),
                'open' => Ticket::where('status','open')->count(),
            ];
        });
        
        // BASE QUERY
        $query = \App\Models\Ticket::query();

        $open = Ticket::where('status','open')->count();
        $pending = Ticket::where('status','pending')->count();
        $progress = Ticket::where('status','on_progress')->count();
        $closed = Ticket::where('status','closed')->count();
        $total = Ticket::count();
        $today = $today = Ticket::whereDate('created_at', Carbon::today())->count();

        // DATA HARIAN (7 hari terakhir)
        $daily = Ticket::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at','>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->pluck('total','date');


        // DATA BULANAN
        $monthly = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total','month');


        // KATEGORI
        $kategori = Ticket::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total','kategori');

        // rata - rata waktu pengerjaan
        $sla_avg = Ticket::whereNotNull('started_at')
        ->whereNotNull('resolved_at')
        ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, started_at, resolved_at)) as avg_sla'))
        ->value('avg_sla');


        $technicianWorkload = Ticket::select(
        'assigned_to',
        DB::raw('COUNT(*) as total_ticket')
        )
        ->whereNotNull('assigned_to')
        ->groupBy('assigned_to')
        ->with('technician')
        ->orderByDesc('total_ticket') // paling sibuk di atas
        ->get();


       // LEADER STATS
        $leaderStats = User::where('role','leader')
        ->withCount([
            'team as total_user',
        ])
        ->get()
        ->map(function($leader){

            // ambil user timnya
            $teamIds = User::where('leader_id', $leader->id)->pluck('id');

            // hitung tiket
            // $tickets = Ticket::whereIn('user_id', $teamIds);

            $tickets = Ticket::where(function($q) use ($teamIds, $leader) {
                $q->whereIn('assigned_to', $teamIds)
                ->orWhereIn('created_by', $teamIds)
                ->orWhere('created_by', $leader->id);
            });


            // if($teamIds->isEmpty()){
            //     return [
            //         'name' => $leader->name,
            //         'total_user' => 0,
            //         'open' => 0,
            //         'progress' => 0,
            //         'pending' => 0,
            //         'closed' => 0,
            //         'total_ticket' => 0,
            //     ];
            // }

            return [
                'name' => $leader->name,
                'total_user' => $teamIds->count(),
                'open' => (clone $tickets)->where('status','open')->count(),
                'progress' => (clone $tickets)->where('status','on_progress')->count(),
                'pending' => (clone $tickets)->where('status','pending')->count(),
                'closed' => (clone $tickets)->where('status','closed')->count(),
                'total_ticket' => (clone $tickets)->count(),
            ];
        });


        return view('admin.dashboard',compact(
        'total','open','progress','closed','today','pending',
        'daily','monthly','kategori','sla_avg','technicianWorkload','leaderStats'));
    }

}