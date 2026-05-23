@extends('adminlte::page')

@section('title', 'My Tickets')

@section('content_header')
    <h1>My Tickets</h1>
@stop

@section('content')

<table id="table-myticket" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Ticket Code</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_code }}</td>
                <td>
                    @if($ticket->status == 'open')
                        <span class="badge bg-info">Open</span>
                    @elseif($ticket->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($ticket->status == 'on_progress')
                        <span class="badge bg-primary">On Progress</span>
                    @elseif($ticket->status == 'closed')
                        <span class="badge bg-success">Closed</span>
                    @endif
                </td>
                <td>{{ $ticket->created_at->format('d-m-Y H:i') }}</td>
               
                <td>
                    <a href="/ticket/{{$ticket->id}}" 
                        class="btn btn-sm btn-info"><i class="fas fa-eye"></i>
                        Detail
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
   
        <!-- FLOATING BUTTON -->
        <div id="wa-button" class="wa-button">
            <i class="fab fa-whatsapp"></i>
        </div>
        <!-- POPUP CHAT -->
        <div id="wa-chat" class="wa-chat-box">
            <div class="wa-header">
                <strong>IT Support</strong>
                <span onclick="toggleWA()">×</span>
            </div>
            <div class="wa-body">
                <p>👋 Halo!</p>
                <p>Butuh bantuan atau follow up tiket?</p>
                <p>Silakan hubungi IT melalui WhatsApp.</p>
            </div>
            <div class="wa-footer">
                <a id="wa-link" target="_blank">
                    <i class="fab fa-whatsapp"></i> Hubungi WA IT
                </a>
            </div>
        </div>

  

</div>
</div>
</div>
</table>
<!-- {{ $tickets->links() }} Pagination -->
@stop

{{-- CSS DataTables --}}
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .wa-floating {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #25D366;
            color: white;
            padding: 12px 16px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 9999;
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }

        /* BUTTON */
        .wa-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #25D366;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 26px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 9999;
        }

        /* CHAT BOX */
        .wa-chat-box {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 280px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 9999;
        }

        /* HEADER */
        .wa-header {
            background: #25D366;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
        }

        /* BODY */
        .wa-body {
            padding: 12px;
            font-size: 14px;
        }

        /* FOOTER */
        .wa-footer {
            padding: 10px;
            border-top: 1px solid #eee;
        }

        .wa-footer a {
            display: block;
            text-align: center;
            background: #25D366;
            color: white;
            padding: 8px;
            border-radius: 8px;
            text-decoration: none;
        }

        /* HOVER */
        .wa-footer a:hover {
            background: #1ebe5d;
        }

        /* ICON */
        .wa-floating i {
            font-size: 20px;
        }

        /* TEXT */
        .wa-floating span {
            font-size: 14px;
        }

        /* HOVER EFFECT */
        .wa-floating:hover {
            background: #1ebe5d;
            transform: scale(1.05);
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(37,211,102,0.5); }
            70% { box-shadow: 0 0 0 10px rgba(37,211,102,0); }
            100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); }
        }
    </style>

    <style>
    .toast-custom {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 320px;
        max-width: 420px;
        padding: 14px 18px;
        border-radius: 12px;
        color: #fff;
        z-index: 99999;
        box-shadow: 0 10px 25px rgba(0,0,0,.15);
        animation: slideIn .3s ease;
        font-size: 14px;
    }

    .toast-content{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .toast-icon{
        font-size:18px;
        font-weight:bold;
    }

    .toast-success{
        background:#28a745;
    }

    .toast-error{
        background:#dc3545;
    }

    .toast-warning{
        background:#ffc107;
        color:#000;
    }

    .toast-hide{
        animation: slideOut .5s ease forwards;
    }

    @keyframes slideIn{
        from{
            opacity:0;
            transform:translateX(100%);
        }
        to{
            opacity:1;
            transform:translateX(0);
        }
    }
    @keyframes slideOut{
        from{
            opacity:1;
            transform:translateX(0);
        }
        to{
            opacity:0;
            transform:translateX(100%);
        }
    }

    </style>
@stop

{{-- JS DataTables --}}
@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {

    $('#table-myticket').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            emptyTable: "Tidak ada tiket"
        }
    });
});
</script>

<script>
function toggleWA() {
    let box = document.getElementById("wa-chat");
    box.style.display = (box.style.display === "flex") ? "none" : "flex";
}

// tombol klik buka popup
document.getElementById("wa-button").onclick = toggleWA;

// AUTO MESSAGE
let phone = "6285183287284";
let message = encodeURIComponent(
    "Halo IT, saya ingin follow up tiket saya."
);

// set link otomatis
document.getElementById("wa-link").href =
    `https://wa.me/${phone}?text=${message}`;
</script>

    <script>
    function showToast(message, type = 'success') {
    let icon = '';
    if(type === 'success') icon = '✔';
    if(type === 'error') icon = '✖';
    if(type === 'warning') icon = '⚠';

    const toast = document.createElement('div');
    toast.className = 'toast-custom toast-' + type;

    toast.innerHTML = `
        <div class="toast-content">
            <span class="toast-icon">${icon}</span>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('toast-hide');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 3000);
    }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            if (!sessionStorage.getItem('toast_success')) {
                showToast(
                    "{{ session('success') }}",
                    'success'
                );
                sessionStorage.setItem(
                    'toast_success',
                    '1'
                );
            }
        @endif
        @if(session('error'))
            if (!sessionStorage.getItem('toast_error')) {
                showToast(
                    "{{ session('error') }}",
                    'error'
                );
                sessionStorage.setItem(
                    'toast_error',
                    '1'
                );
            }
        @endif
    });

    window.addEventListener('beforeunload', function () {
        sessionStorage.removeItem('toast_success');
        sessionStorage.removeItem('toast_error');

    });
</script>


@stop
