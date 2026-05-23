<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('technician');

        // FILTER TANGGAL
        if($request->start_date && $request->end_date){
            $query->whereDate('created_at', '>=', $request->start_date)
                ->whereDate('created_at', '<=', $request->end_date);
        }

        // FILTER STATUS
        if($request->status){
            $query->where('status', $request->status);
        }

        // FILTER KATEGORI
        if($request->kategori){
            $query->where('kategori', $request->kategori);
        }

        // FILTER TEKNISI
        if($request->assigned_to){
            $query->where('assigned_to', $request->assigned_to);
        }

        $tickets = $query->orderBy('created_at','desc')->get();

        // ambil data teknisi (admin saja)
        $technicians = User::where('role','admin')->get();

        return view('admin.report.index', compact('tickets','technicians'));
    }

    public function export(Request $request)
    {
        $query = Ticket::with('technician');

        if($request->start_date && $request->end_date){
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if($request->status){
            $query->where('status', $request->status);
        }

        if($request->kategori){
            $query->where('kategori', $request->kategori);
        }

        if($request->assigned_to){
            $query->where('assigned_to', $request->assigned_to);
        }

        $tickets = $query->get();

        // export sederhana (CSV)
        $filename = "report_ticket.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Kode',
                'Nama',
                'Kategori',
                'Status',
                'IT',
                'Created At',
                'Started At',
                'Resolved At',
                'Durasi (Menit)'
            ]);

            foreach ($tickets as $t) {
                fputcsv($file, [
                    $t->ticket_code,
                    $t->nama,
                    $t->kategori,
                    $t->status,
                    $t->technician->name ?? '-',
                    $t->created_at,
                    $t->started_at,
                    $t->resolved_at,
                    $t->started_at && $t->resolved_at 
                        ? (int) $t->started_at->diffInMinutes($t->resolved_at)
                        : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}