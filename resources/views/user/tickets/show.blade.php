@extends('adminlte::page')

@section('title','Ticket Detail')

@section('content_header')
<h1>Ticket Detail</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        Ticket Code : {{ $ticket->ticket_code }}
    </div>
        <div class="card-body">
            <p><b>Nama</b> : {{ $ticket->nama }}</p>
            <p><b>Nomor Meja</b> : {{ $ticket->nomor_meja }}</p>
            <p><b>Nomor Ruangan</b> : {{ $ticket->nomor_ruangan }}</p>
            <p><b>IP Address</b> : {{ $ticket->ip_address }}</p>
            <p><b>Nomor WhatsApp</b> : {{ $ticket->no_whatsapp }}</p>
            <p><b>Status</b> : {{ $ticket->status }}</p>
            <p><b>Deskripsi</b></p>
            <p>{{ $ticket->deskripsi }}</p>
        </div>
</div>
<a id="btn-wa"
   target="_blank"
   class="btn btn-success mt-3">

   <i class="fab fa-whatsapp"></i> Follow Up ke IT
</a>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@stop

@section('js')

    <script>
        let phone = "6285183287284";

        // ambil data dari blade
        let ticketId = "{{ $ticket->ticket_code }}";
        let desc = "{{ $ticket->deskripsi }}";
        let no_meja  = "{{ $ticket->nomor_meja }}";
        let no_ruang  = "{{ $ticket->nomor_ruangan }}";
        let status = "{{ $ticket->status }}";
        let user = "{{ auth()->user()->name }}";

        // template pesan
        let message = `
        Halo IT, saya ingin follow up tiket.

        No Tiket    : #${ticketId}
        No Meja     : ${no_meja}
        No Ruang    : ${no_ruang}
        kendala     : ${desc}
        Status      : ${status}

        User     : ${user}

        Mohon dibantu ya, terima kasih.
        `;

        // encode URL
        let url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;

        // set ke tombol
        document.getElementById("btn-wa").href = url;
    </script>

@stop