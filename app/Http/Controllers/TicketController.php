<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use App\Models\TicketTimeline;
use Carbon\Carbon;

class TicketController extends Controller
{

    public function index()
        {

            $tickets = Ticket::where('user_id',Auth::id())
                        ->latest()
                        ->paginate(10);

            return view('user.tickets.mytickets',compact('tickets'));

        }

    public function create()
        {
            return view('user.tickets.create');
        }

    public function store(Request $request)
    {

        $request->validate([
            'nama'=>'required',
            'nomor_meja'=>'required',
            'nomor_ruangan'=>'required',
            'no_whatsapp'=> 'required',
            'deskripsi'=>'required'
        ]);

        $ticket_code = 'MPTB-IT-'.date('Y').'-'.rand(1000,9999);

        $screenshot = null;
        $ip_address = null;
        $kategori = null;
        $assign_to = null;

        if($request->hasFile('screenshot')){

            $screenshot = $request->file('screenshot')
                        ->store('tickets','public');

        }

        $ticket= Ticket::create([

            'ticket_code'=>$ticket_code,
            'user_id'=>Auth::id(),
            'nama'=>$request->nama,
            'nomor_meja'=>$request->nomor_meja,
            'nomor_ruangan'=>$request->nomor_ruangan,
            'no_whatsapp' =>$request->no_whatsapp,
            'ip_address'=>$request->ip_address,
            'deskripsi'=>$request->deskripsi,
            'screenshot'=>$screenshot,
            'status'=>'open',
            'kategori'=>$kategori,
            'created_by'=>auth()->id(),
            'assigned_to' => $assign_to

        ]);

        \Illuminate\Support\Facades\Cache::flush();
        
        $admins = User::where('role','admin')->get();
        foreach($admins as $admin){
        $admin->notify(new NewTicketNotification($ticket));}

        return redirect('/my-tickets')
            ->with('success','Ticket berhasil dibuat');

    }

    public function show($id)
    {
        $ticket = Ticket::with('histories','comments')->findOrFail($id);

        // user tidak bisa membuat tiket orang
        if($ticket->user_id != auth()->id()){
            abort(403);
        }

        return view('user.tickets.show', compact('ticket'));
    }

    public function update(Request $request,$id)
    {

    $ticket = Ticket::findOrFail($id);

    $oldStatus = $ticket->status;

    $ticket->update([

    'status'=>$request->status,

    'kategori'=>$request->kategori,

    'assigned_to'=>$request->assigned_to

    ]);

    // 🔥 Set waktu SLA
    if ($oldStatus != 'on_progress' && $request->status == 'on_progress') {
        $ticket->started_at = Carbon::now();
    }

    if ($oldStatus != 'closed' && $request->status == 'closed') {
        $ticket->resolved_at = Carbon::now();
    }

    $ticket->save();

    // 🔥 Simpan ke timeline
    TicketTimeline::create([
        'ticket_id' => $ticket->id,
        'status' => $request->status,
        'description' => 'Status diubah ke ' . $request->status
    ]);

    return back()->with('success', 'Ticket berhasil diupdate');

    }


}
