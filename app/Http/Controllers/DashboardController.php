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

        // BASE QUERY SESUAI ROLE
        $query = Ticket::query();

        if($user->role == 'user'){
            $query->where('user_id', $user->id);
        }

        if($user->role == 'leader'){
            $teamIds = User::where('leader_id', $user->id)->pluck('id');

            $query->where(function($q) use ($teamIds, $user){
                $q->whereIn('user_id', $teamIds)
                ->orWhere('user_id', $user->id);
            });
        }

        // CACHE PER ROLE (penting)
        $cacheKey = 'dashboard_'.$user->role.'_'.$user->id;
        $stats = Cache::remember($cacheKey, 30, function() use ($query){
            return [
                'total' => (clone $query)->count(),
                'open' => (clone $query)->where('status','open')->count(),
                'progress' => (clone $query)->where('status','on_progress')->count(),
                'pending' => (clone $query)->where('status','pending')->count(),
                'closed' => (clone $query)->where('status','closed')->count(),
                'cancelled' => (clone $query)->where('status','cancelled')->count(),
            ];
        });

        // ASSIGN
        $total = $stats['total'];
        $open = $stats['open'];
        $progress = $stats['progress'];
        $pending = $stats['pending'];
        $closed = $stats['closed'];
        $cancelled = $stats['cancelled'];

        // TODAY
        $today = (clone $query)->whereDate('created_at', now())->count();

        // DAILY
        $daily = (clone $query)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total','date');

        // MONTHLY
        // $monthly = (clone $query)
        //     ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        //     ->groupBy('month')
        //     ->pluck('total','month');

        $monthly = (clone $query)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $bulanIndonesia = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agust',
            9 => 'Sept',
            10 => 'Okt',
            11 => 'Nove',
            12 => 'Des',
        ];

        $monthlyLabels = $monthly->map(function ($item) use ($bulanIndonesia) {
            return $bulanIndonesia[$item->month];
        });

        $monthlyValues = $monthly->pluck('total');

        // KATEGORI
        $kategori = (clone $query)
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total','kategori');

        // SLA
        $sla_avg = Ticket::whereNotNull('started_at')
            ->whereNotNull('resolved_at')
            ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, started_at, resolved_at)'));

        // WORKLOAD
        $technicianWorkload = Ticket::select(
            'assigned_to',
            DB::raw('COUNT(*) as total_ticket')
        )
        ->whereNotNull('assigned_to')
        ->groupBy('assigned_to')
        ->with('technician')
        ->orderByDesc('total_ticket')
        ->get();

        // LEADER STATS
        $leaderStats = User::where('role','leader')
        ->get()
        ->map(function($leader){

            $teamIds = User::where('leader_id', $leader->id)->pluck('id');

            $tickets = Ticket::where(function($q) use ($teamIds, $leader){
                $q->whereIn('user_id', $teamIds)
                ->orWhere('user_id', $leader->id);
            });

            return [
                'name' => $leader->name,
                'total_user' => $teamIds->count(),
                'open' => (clone $tickets)->where('status','open')->count(),
                'progress' => (clone $tickets)->where('status','on_progress')->count(),
                'pending' => (clone $tickets)->where('status','pending')->count(),
                'closed' => (clone $tickets)->where('status','closed')->count(),
                'cancelled' => (clone $tickets)->where('status','cancelled')->count(),
                'total_ticket' => (clone $tickets)->count(),
            ];

        });

        return view('admin.dashboard', compact(
            'total',
            'open',
            'progress',
            'pending',
            'closed',
            'cancelled',
            'today',
            'daily',
            'monthly',
            'monthlyLabels',
            'monthlyValues',
            'kategori',
            'sla_avg',
            'technicianWorkload',
            'leaderStats'
        ));
    }


    public function realtime()
    {
        try{

            $daily = Ticket::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->pluck('total','date');

            // $monthly = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            //     ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            //     ->groupBy('month')
            //     ->orderBy('month')
            //     ->get();

            $monthly = Ticket::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

            $bulanIndonesia = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agust',
                9 => 'Sept',
                10 => 'Okt',
                11 => 'Nove',
                12 => 'Des',
            ];

            $monthlyLabels = $monthly->map(function($item) use ($bulanIndonesia){
                return $bulanIndonesia[$item->bulan];
            });

            $monthlyValues = $monthly->pluck('total')->values();


            $kategori = Ticket::selectRaw('kategori, COUNT(*) as total')
                ->groupBy('kategori')
                ->pluck('total','kategori');

            $technicianWorkload = Ticket::select(
                    'assigned_to',
                    DB::raw('COUNT(*) as total_ticket')
                )
                ->whereNotNull('assigned_to')
                ->groupBy('assigned_to')
                ->with('technician')
                ->get();

            return response()->json([
                'total' => Ticket::count(),
                'open' => Ticket::where('status','open')->count(),
                'progress' => Ticket::where('status','on_progress')->count(),
                'pending' => Ticket::where('status','pending')->count(),
                'closed' => Ticket::where('status','closed')->count(),
                'cancelled' => Ticket::where('status','cancelled')->count(),
                'daily' => $daily,
                // 'monthly' => $monthly,
                'monthly_labels' => $monthlyLabels,
                'monthly_values' => $monthlyValues,
                'kategori' => $kategori,
                'workload_labels' =>
                    $technicianWorkload->map(function($t){
                        return $t->technician->name ?? '-';
                    }),
                'workload_values' =>
                    $technicianWorkload->pluck('total_ticket'),
            ]);

        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ],500);
        }
    }
}