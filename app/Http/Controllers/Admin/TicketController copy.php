<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\TicketComment;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TicketUpdateNotification;
use App\Models\TicketTimeline;
use Carbon\Carbon;
use App\Models\User;


class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets_open = Ticket::where('status','open')
        ->orderBy('created_at','desc')
        ->paginate(20);

        $tickets_progress = Ticket::where('status','on_progress')
        ->orderBy('started_at','desc')
        ->paginate(20);

        $tickets_pending = Ticket::where('status','pending')
        ->orderBy('updated_at','desc')
        ->paginate(20);

        $tickets_closed = Ticket::where('status','closed')
        ->orderBy('resolved_at','desc')
        ->paginate(20);

        return view('admin.tickets.index', compact('tickets_open','tickets_progress','tickets_pending','tickets_closed'));
    }

    public function show($id)
    {
        $ticket = Ticket::with('histories','comments.user','timelines')->findOrFail($id);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function edit($id){
        $ticket = Ticket::with('comments.user')->findOrFail($id);

        // // PROTEKSI
        // if($ticket->assigned_to != Auth::id()){
        //     return redirect()->back()->with('error','Bukan tiket kamu');
        // }

        $technicians = User::whereIn('role',['admin','technician'])->get();

        return view('admin.tickets.edit',compact('ticket','technicians'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $oldStatus = $ticket->status;

        if($ticket->assigned_to != Auth::id()){
            return redirect('/admin/tickets')
                ->with('error','Bukan tiket kamu');
        }

        // proteksi assign
        if($ticket->assigned_to != Auth::id()){
            // paksa tidak boleh ubah assign
            $request->merge([
                'assigned_to' => $ticket->assigned_to
            ]);
        }

        // Update tiket
        $ticket->update([
            'status' => $request->status,
            'kategori' => $request->kategori,
            'assigned_to' => $request->assigned_to
        ]);

         // 🔥 Set waktu SLA
        if ($oldStatus != 'on_progress' && $request->status == 'on_progress') {
            $ticket->started_at = Carbon::now();
        }

        if ($oldStatus != 'closed' && $request->status == 'closed') {
            $ticket->resolved_at = Carbon::now();
        }

        if($request->assigned_to != $ticket->assigned_to){
        // log timeline
        }

        $ticket->save();


        // Simpan history sebelum update
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'status' => $request->status,
            'keterangan' => "Admin " . Auth::user()->name . " mengupdate tiket",
            'updated_by' => Auth::id()
        ]);

        $ticket->user->notify(new TicketUpdateNotification($ticket));

        return back()->with('success', 'Ticket berhasil diupdate');

        return redirect('/admin/tickets')
        ->with('success','Ticket berhasil diupdate');

    }

    public function takeTicket($id)
{
    $ticket = Ticket::findOrFail($id);

    // hanya boleh ambil jika masih open
    if($ticket->status != 'open'){
        return redirect('/admin/tickets')
            ->with('error','Ticket sudah diambil');
    }

    $ticket->update([
        'status' => 'on_progress', // ✅ FIX DI SINI
        'assigned_to' => Auth::id(),
        'started_at' => now()
    ]);

    return redirect('/admin/tickets')
        ->with('success','Ticket berhasil diambil & mulai dikerjakan');
}

    public function comment(Request $request,$id)
    {
        $request->validate([
            'comment'=>'required'
        ]);

        TicketComment::create([
            'ticket_id'=>$id,
            'user_id'=>Auth::id(),
            'comment'=>$request->comment
        ]);

        return back()->with('success','Komentar berhasil ditambahkan');
    }


    public function TicketAdmin()
    {
        $tickets = Ticket::where('assigned_to', Auth::id())
            ->whereIn('status',['on_progress','pending'])
            ->orderBy('updated_at','desc')
            ->get();

        return view('admin.tickets.my_ticket', compact('tickets'));
    }



    public function reassign(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // hanya yang pegang tiket yang bisa oper
        if ($ticket->assigned_to != Auth::id()) {
            return redirect('/admin/tickets')->with('error','Bukan tiket kamu');
        }

        $oldTechnician = $ticket->assigned_to;
        $newTechnician = $request->assigned_to;

        // update tiket
        $ticket->update([
            'assigned_to' => $newTechnician,
            'status' => 'on_progress' // tetap dikerjakan
        ]);

        // simpan ke timeline
        \App\Models\TicketTimeline::create([
            'ticket_id' => $ticket->id,
            'status' => 'reassigned',
            'description' => 'Tiket dioper ke teknisi lain',
            'assigned_to' => $newTechnician,
        ]);

        return redirect('/admin/tickets')->with('success','Tiket berhasil dioper');
    }


    public function destroy($id)
    {

        $ticket = Ticket::findOrFail($id);

        $ticket->delete();

        return redirect('/admin/tickets')
        ->with('success','Ticket berhasil dihapus');

    }

}
