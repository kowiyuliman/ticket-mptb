@extends('adminlte::page')
@section('title','Admin Tickets')

@section('content_header')
<div class="d-flex justify-content-between align-items-center flex-wrap">
    <h1 class="mb-2">
        Admin Tickets
    </h1>
</div>
@stop

@section('content')
<br>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-info">
             <h3 class="card-title text-white mb-0">
                Open Tickets
            </h3>
        </div>
        <div class="card-body table-responsive">
             <table id="table-open" class="table table-bordered table-hover">
                <thead class="bg-primary bg-info">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Ip Address</th>
                        <th>Trouble</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="open-body">
                    @foreach($tickets_open as $ticket)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ticket->ticket_code }}</td>
                            <td>{{ $ticket->nama }}</td>
                            <td><span class="badge bg-info">Open</span></td>
                            <td>{{ $ticket->ip_address }}</td>
                            <td>
                                {{ \Illuminate\Support\Str::limit($ticket->deskripsi, 40) }}
                            </td>
                            <td>{{ $ticket->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <form action="/admin/ticket/take/{{ $ticket->id }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm" title="Ambil Ticket">
                                        <i class="fas fa-hand-paper"></i>
                                    </button>
                                    <a href="/admin/ticket/show/{{$ticket->id}}" 
                                    class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </form>
                                <!-- <form action="/admin/ticket/cancel/{{ $ticket->id }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin cancel ticket?')"
                                        title="Cancel">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form> -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>

<br>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary">
        <h3 class="card-title text-white mb-0">
            On Progress Tickets
        </h3>
    </div>
    <div class="card-body table-responsive">
        <table id="table-progress" class="table table-bordered table-hover">
            <thead class="bg-primary text-white">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Trouble</th>
                    <th>IT</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="progress-body">
                    @foreach($tickets_progress as $ticket)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ticket->ticket_code }}</td>
                            <td>{{ $ticket->nama }}</td>
                            <td><span class="badge bg-primary">On Progress</span></td>
                            <td>
                                {{ \Illuminate\Support\Str::limit($ticket->deskripsi, 40) }}
                            </td>
                            <td>{{ $ticket->technician->name ?? '-' }}</td>
                            <td>
                                <a href="/admin/ticket/edit/{{ $ticket->id }}" 
                                class="btn btn-warning btn-sm action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/ticket/show/{{$ticket->id}}" 
                                class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <!-- <form action="/admin/ticket/cancel/{{ $ticket->id }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin cancel ticket?')"
                                title="Cancel">
                                <i class="fas fa-times"></i>
                            </button>
                        </form> -->
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
</div>

<br>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-warning">
        <h3 class="card-title text-white mb-0">
            Pending Ticket
        </h3>
    </div>
    <div class="card-body table-responsive">
        <table id="table-pending" class="table table-bordered table-hover">
                <thead class="bg-warning text-white">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>IT</th>
                        <th>Remaks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="pending-body">
                    @foreach($tickets_pending as $ticket)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ticket->ticket_code }}</td>
                            <td>{{ $ticket->nama }}</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td>{{ $ticket->technician->name ?? '-' }}</td>
                            <td>
                                {{ \Illuminate\Support\Str::limit($ticket->deskripsi, 40) }}
                            </td>
                            <td>
                                <a href="/admin/ticket/edit/{{ $ticket->id }}" 
                                class="btn btn-warning btn-sm action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/ticket/show/{{$ticket->id}}" 
                                class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <!-- <form action="/admin/ticket/cancel/{{ $ticket->id }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin cancel ticket?')"
                                    title="Cancel">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form> -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</div>

<br>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success">
        <h3 class="card-title text-white mb-0">
            Closed Ticket
        </h3>
    </div>
    <div class="card-body table-responsive">
        <table id="table-closed" class="table table-bordered table-hover">
            <thead class="bg-success text-white">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>IT</th>
                    <th>Remaks</th>
                    <th>Durasi Pengerjaan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="closed-body">
                @foreach($tickets_closed as $ticket)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ticket->ticket_code }}</td>
                        <td>{{ $ticket->nama }}</td>
                        <td>
                            <span class="badge bg-success">Closed</span>
                        </td>
                        <td>{{ $ticket->technician->name ?? '-' }}</td>
                        <td>
                            {{ \Illuminate\Support\Str::limit($ticket->deskripsi, 40) }}
                        </td>
                        <td>
                            @if($ticket->started_at && $ticket->resolved_at)
                                @php
                                    $minutes = (int) $ticket->started_at->diffInMinutes($ticket->resolved_at);
                                @endphp
                                <span class="badge bg-success">
                                {{ $ticket->durasi_menit ?? '-'  }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="/admin/ticket/show/{{$ticket->id}}" 
                            class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

{{-- CSS DataTables --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        td:first-child, th:first-child {
                text-align: center;
                width: 50px;
            }
        .table-responsive{
                overflow-x:auto;
            }
        table.dataTable{
                width:100% !important;
            }
        .table td,
        .table th{
                vertical-align: middle;
                white-space: nowrap;
            }

        .action-btn{
            margin:2px;
            border-radius:8px;
            min-width:36px;
        }

    @media(max-width:768px)
    {

        h1{
            font-size:22px;
        }

        h3{
            font-size:18px;
        }

        .table{
            font-size:12px;
        }

        .btn-sm{
            padding:4px 8px;
            font-size:11px;
        }

        .badge{
            font-size:10px;
        }

        .toast-custom{
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 280px;
            max-width: 350px;
            padding: 15px 20px;
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            z-index: 9999;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: slideIn 0.4s ease;
        }

        .table-hover tbody tr:hover{
            background:#f4f6f9;
            transition:0.2s;
        }

        .dataTables_processing{
            z-index:9999;
        }

        .dataTables_filter input{
            width:100% !important;
            margin-top:10px;
        }

        .dataTables_length,
        .dataTables_filter{
            text-align:left !important;
        }
    }
            
    </style>

    <style>
            .toast-custom {
                position: fixed;
                top: 20px;
                right: 20px;
                min-width: 280px;
                padding: 15px 20px;
                border-radius: 10px;
                color: #fff;
                font-size: 14px;
                z-index: 9999;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                animation: slideIn 0.5s ease;
            }

            .toast-success { background: #28a745; }
            .toast-error { background: #dc3545; }
            .toast-warning { background: #ffc107; color:#000; }

            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
    </style>
@stop


{{-- JS DataTables --}}
@section('js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-open').DataTable({
                responsive: true,
                autoWidth: false,
                scrollX: true,
                pageLength: 5,
                lengthMenu: [5,10,25,50],
                language: {
                    emptyTable: "Tidak ada tiket"
                }
            });

            $('#table-progress').DataTable({
                responsive: true,
                autoWidth: false,
                scrollX: true,
                pageLength: 5,
                lengthMenu: [5,10,25,50],
                language: {
                    emptyTable: "Tidak ada tiket"
                }
            });

            $('#table-pending').DataTable({
                 responsive: true,
                autoWidth: false,
                scrollX: true,
                pageLength: 5,
                lengthMenu: [5,10,25,50],
                language: {
                    emptyTable: "Tidak ada tiket"
                }
            });

            $('#table-closed').DataTable({
                 responsive: true,
                autoWidth: false,
                scrollX: true,
                pageLength: 5,
                lengthMenu: [5,10,25,50],
                language: {
                    emptyTable: "Tidak ada tiket"
                }
            });

        });
    </script>

    <script>
        // function showToast('Ticket baru masuk', 'success') {
        //     let toast = document.createElement('div');
        //     toast.className = 'toast-custom toast-' + type;
        //     toast.innerHTML = message;

        //     document.body.appendChild(toast);

        //     setTimeout(() => {
        //         toast.style.opacity = '0';
        //         toast.style.transform = 'translateX(100%)';
        //         setTimeout(() => toast.remove(), 500);
        //     }, 4000);
        // }

        function showToast(message, type = 'success')
        {
            let toast = document.createElement('div');
            toast.className =
                'toast-custom toast-' + type;
            toast.innerHTML = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    toast.remove();
                },500);
            },3000);
        }
    </script>

    <script>
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>

    <!-- <script>
        $('#table-cancel').DataTable({
        order: [[0, 'desc']],
        responsive: true
        });
    </script> -->

    <script>
        async function loadTickets(){
                try{
                    const response = await fetch('/admin/ticket/fetch');
                    const data = await response.json();
                    // pertama kali load
                    if(lastOpenCount === 0){
                        lastOpenCount = data.open.length;
                        lastProgressCount = data.progress.length;
                        // lastPendingCount = data.pending.length;
                        // lastClosedCount = data.closed.length;
                    }else{
                        // OPEN
                        if(data.open.length > lastOpenCount){
                            showToast(
                                'Ticket baru masuk',
                                'success'
                            );
                            playSound();
                        }
                        // PROGRESS
                        if(data.progress.length > lastProgressCount){
                            showToast(
                                'Ticket masuk ke Progress',
                                'warning'
                            );
                        // }
                        // // PENDING
                        // if(data.pending.length > lastPendingCount){

                        //     showToast(
                        //         'Ticket menjadi Pending',
                        //         'warning'
                        //     );
                        // }

                        // // CLOSED
                        // if(data.closed.length > lastClosedCount){
                        //     showToast(
                        //         'Ticket berhasil diselesaikan',
                        //         'success'
                        //     );
                        // }

                        lastOpenCount = data.open.length;
                        lastProgressCount = data.progress.length;
                        // lastPendingCount = data.pending.length;
                        // lastClosedCount = data.closed.length;
                    }

                    updateOpenTable(data.open);
                    updateProgressTable(data.progress);
                    // updatePendingTable(data.pending);

                    // updateClosedTable(data.closed);

                }
                catch(error){
                    console.log(error);
                }
            }}
    </script>

    <script>
        function updateOpenTable(tickets){
            let html = '';
            tickets.forEach((ticket, index) => {
                html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${ticket.ticket_code}</td>
                    <td>${ticket.nama}</td>
                    <td>
                        <span class="badge bg-info">
                            Open
                        </span>
                    </td>
                    <td>${ticket.ip_address ?? '-'}</td>
                    <td>${ticket.deskripsi ?? '-'}</td>
                    <td>${formatDate(ticket.created_at)}</td>
                    <td>
                        <form action="/admin/ticket/take/${ticket.id}" method="POST">
                            <button class="btn btn-success btn-sm action-btn">
                                <i class="fas fa-hand-paper"></i>
                            </button>
                            <a href="/admin/ticket/show/${ticket.id}"
                            class="btn btn-info btn-sm action-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                        </form>
                    </td>
                </tr>
                `;
            });
            document.getElementById('open-body').innerHTML = html;
        }
    </script>

    <script>
        function showNotif(data){
                // suara
                let audio = new Audio('/sound/notification.mp3');
                audio.play();
                // browser notif
                if(Notification.permission === 'granted'){
                    new Notification('Ticket Baru', {
                        body: data.message,
                        icon: '/favicon.ico'
                    });
                }

                // popup html
                let notif = document.createElement('div');
                notif.className = 'ticket-popup';
                notif.innerHTML = `
                    <div style="font-weight:bold;">
                        Ticket Baru
                    </div>

                    <div style="margin-top:5px;">
                        ${data.message}
                    </div>
                `;
                document.body.appendChild(notif);
                setTimeout(() => {
                    notif.remove();
                }, 5000);
            }
    </script>   


    //load ticket
    <script>
        loadTickets();
        setInterval(() => {
            loadTickets();
        }, 5000);
    </script>

    <script>
        function formatDate(dateString){
            const date = new Date(dateString);
            return date.toLocaleString('id-ID');
        }
        function playSound(){
            let audio = new Audio('/sound/notification.mp3');
            audio.play().catch(function(err){
                console.log(err);
            });
        }
    </script>
@stop