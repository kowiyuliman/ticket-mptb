@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1 class="mb-3">Ticketing Dashboard</h1>
@stop

@section('content')

    {{-- STATISTIK --}}
    <div class="row">

        @php
            $cards = [
                ['title'=>'Total Ticket','value'=>$total,'color'=>'info','icon'=>'fas fa-ticket-alt'],
                ['title'=>'Open','value'=>$open,'color'=>'warning','icon'=>'fas fa-folder-open'],
                ['title'=>'On Progress','value'=>$progress,'color'=>'primary','icon'=>'fas fa-spinner'],
                ['title'=>'Pending','value'=>$pending,'color'=>'danger','icon'=>'fas fa-pause-circle'],
                ['title'=>'Closed','value'=>$closed,'color'=>'success','icon'=>'fas fa-check-circle'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-3">
            <div class="small-box bg-{{ $card['color'] }}">
                <div class="inner">
                    <h3 id="card-{{ strtolower(str_replace(' ','-',$card['title'])) }}">
                        {{ $card['value'] }}
                    </h3>
                    <p>{{ $card['title'] }}</p>
                </div>
                <div class="icon">
                    <i class="{{ $card['icon'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    <div class="col-md-12">
            <div class="card-header">
                <h3 class="card-title">Statistik Tiket</h3>
            </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="leaderTable">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>No</th>
                            <th>Team Leader</th>
                            <th>Jumlah Tim</th>
                            <th>Total Ticket</th>
                            <th>Open</th>
                            <th>Progress</th>
                            <th>Pending</th>
                            <th>Closed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaderStats as $l)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $l['name'] }}</td>
                            <td>{{ $l['total_user'] }}</td>
                            <td><b>{{ $l['total_ticket'] }}</b></td>
                            <td>
                                <span class="badge-stat badge-open">
                                    {{ $l['open'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-stat badge-progress">
                                    {{ $l['progress'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-stat badge-pending">
                                    {{ $l['pending'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-stat badge-closed">
                                    {{ $l['closed'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CHART ROW 1 --}}
    <div class="row mt-3">
        <div class="col-lg-6 col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Ticket Harian</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Ticket Bulanan</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART ROW 2 --}}
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Kategori Ticket</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Workload IT</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="workloadChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        {{-- Quick Info --}}
        <div class="col-md-6">
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Quick Info</h3>
                </div>
                <div class="card-body">
                    <p>
                        <b>Total Ticket :</b>
                        <span class="badge bg-primary">
                            {{ $total }}
                        </span>
                    </p>
                    <p>
                        <b>Open :</b>
                        <span class="badge-stat badge-open">
                            {{ $open }}
                        </span>
                        </p>
                    <p>
                        <b>On Progress :</b>
                        <span class="badge-stat badge-progress">
                            {{ $progress }}
                        </span>
                    </p>
                    <p>
                        <b>Closed :</b>
                        <span class="badge-stat badge-closed">
                            {{ $closed }}
                        </span>
                    </p>
                    
                    <div class="card card-outline card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Workload Teknisi</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama IT</th>
                                        <th>Total Ticket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($technicianWorkload as $tech)
                                        <tr>
                                            <td>
                                                {{ $tech->technician->username ?? '-' }}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $tech->total_ticket }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="col-md-6">
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Statistik Tiket per-leader</h3>
                </div>
                <div class="card-body">
                    <canvas id="leaderChart"></canvas>
                </div>
            </div>
        </div> -->
    </div>
</div>

<audio id="notifSound" preload="auto">
    <source src="/sound/notification.mp3" type="audio/mpeg">
</audio>

@stop

@section('js')

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>

    // =========================
    // GLOBAL VARIABLE
    // =========================

    let dailyChart;
    let monthlyChart;
    let kategoriChart;
    let workloadChart;
    // let leaderChart;

    let lastTicketId = 0;

    // =========================
    // DOCUMENT READY
    // =========================

    $(document).ready(function () {

        // DATATABLE
        $('#leaderTable').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            pageLength: 5,
            lengthMenu: [5,10,25,50],
        });

        // CHART OPTIONS
        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        };

        // DAILY CHART
        dailyChart = new Chart(
            document.getElementById('dailyChart'),
            {
                type: 'line',
                data: {
                    labels: {!! json_encode($daily->keys()) !!},
                    datasets: [{
                        label: 'Ticket Harian',
                        data: {!! json_encode($daily->values()) !!},
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0,123,255,0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: options
            }
        );

        // MONTHLY CHART
        monthlyChart = new Chart(
            document.getElementById('monthlyChart'),
            {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthly->keys()) !!},
                    datasets: [{
                        label: 'Ticket Bulanan',
                        data: {!! json_encode($monthly->values()) !!},
                        backgroundColor: '#28a745'
                    }]
                },
                options: options
            }
        );


        // KATEGORI CHART
        kategoriChart = new Chart(
            document.getElementById('kategoriChart'),
            {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($kategori->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($kategori->values()) !!},
                        backgroundColor: [
                            '#dc3545',
                            '#007bff',
                            '#ffc107',
                            '#28a745'
                        ]
                    }]
                },
                options: options
            }
        );

        // WORKLOAD CHART
        workloadChart = new Chart(
            document.getElementById('workloadChart'),
            {
                type: 'bar',
                data: {
                    labels: {!! json_encode($technicianWorkload->pluck('technician.name')) !!},
                    datasets: [{
                        label: 'Jumlah Ticket',
                        data: {!! json_encode($technicianWorkload->pluck('total_ticket')) !!},
                        backgroundColor: '#007bff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            }
        );

        // LEADER CHART
        // const leaderData = @json($leaderStats);

        // leaderChart = new Chart(
        //     document.getElementById('leaderChart'),
        //     {
        //         type: 'bar',
        //         data: {
        //             labels: leaderData.map(l => l.name),
        //             datasets: [{
        //                 label: 'Total Ticket',
        //                 data: leaderData.map(l => l.total_ticket),
        //                 backgroundColor: '#6c757d'
        //             }]
        //         },
        //         options: options
        //     }
        // );


        // NOTIF PERMISSION
        if(Notification.permission === 'default'){
            Notification.requestPermission()
            .then(permission => {
                console.log('Notification:', permission);
            });
        }

        // START REALTIME
        loadDashboardRealtime();

        setInterval(() => {
            loadDashboardRealtime();
        }, 5000);

        setInterval(() => {
            checkNewTicket();
        }, 5000);

    let audioUnlocked = false;

    document.addEventListener('click', function () {

        if(audioUnlocked) return;

        const audio = document.getElementById('notifSound');

        audio.play()
            .then(() => {
                audio.pause();
                audio.currentTime = 0;
                audioUnlocked = true;
                console.log('Audio unlocked');
            })
            .catch(err => {
                console.log(err);
            });

    }, { once:true });


    // CHECK NEW TICKET
    async function checkNewTicket() {
        try {
            const response = await fetch('/admin/check-ticket');
            const data = await response.json();
            if (lastTicketId == 0) {
                lastTicketId = data.ticket_id;
                return;
            }
            if (data.ticket_id > lastTicketId) {
                lastTicketId = data.ticket_id;
                showNotif(data);
            }
        } catch (error) {
            console.log(error);
        }
    }

    // SHOW NOTIFICATION
    function showNotif(data) {
        // SOUND
        const audio = document.getElementById('notifSound');
        if(audio){
            audio.currentTime = 0;
            audio.play().catch(err=>{
                console.log('Audio blocked', err);
            });
        }

        // BROWSER NOTIFICATION
        if(Notification.permission === 'granted'){

            new Notification('Ticket Baru', {
                body: data.message,
                icon: '/favicon.ico',
                requireInteraction: true
            });
        }

        // POPUP DASHBOARD
        let notif = document.createElement('div');
        notif.className = 'ticket-popup';
        notif.innerHTML = `
            <div style="font-weight:bold">
                Ticket Baru
            </div>
            <div style="margin-top:5px">
                ${data.message}
            </div>
        `;

        document.body.appendChild(notif);
        setTimeout(() => {
            notif.remove();
        }, 5000);
    }

    // REALTIME DASHBOARD
    async function loadDashboardRealtime() {
        try {
            const response = await fetch('/admin/dashboard/realtime');
            const data = await response.json();
            // CARD
            document.getElementById('card-total-ticket').innerText = data.total;
            document.getElementById('card-open').innerText = data.open;
            document.getElementById('card-on-progress').innerText = data.progress;
            document.getElementById('card-pending').innerText = data.pending;
            document.getElementById('card-closed').innerText = data.closed;
            // DAILY
            dailyChart.data.labels = Object.keys(data.daily);
            dailyChart.data.datasets[0].data = Object.values(data.daily);
            dailyChart.update();
            // MONTHLY
            monthlyChart.data.labels = Object.keys(data.monthly);
            monthlyChart.data.datasets[0].data = Object.values(data.monthly);
            monthlyChart.update();
            // KATEGORI
            kategoriChart.data.labels = Object.keys(data.kategori);
            kategoriChart.data.datasets[0].data = Object.values(data.kategori);
            kategoriChart.update();
            // WORKLOAD
            workloadChart.data.labels = data.workload_labels;
            workloadChart.data.datasets[0].data = data.workload_values;
            workloadChart.update();
        } catch (error) {
            console.log(error);
        }
    }
});
</script>

@stop


@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .ticket-popup{
            position: fixed;
            top: 20px;
            right: 20px;
            width: 320px;
            background: white;
            border-left: 5px solid #28a745;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 999999;
            animation: slideIn .4s ease;
        }

        @keyframes slideIn{
            from{
                transform: translateX(100%);
                opacity:0;
            }
            to{
                transform: translateX(0);
                opacity:1;
            }
        }
       
        /* BASE STYLE */
        .badge-stat {
            font-size: 14px;
            padding: 6px 12px;
            font-weight: 600;
            border-radius: 8px;
            display: inline-block;
            min-width: 45px;
            text-align: center;
        }

        /* OPEN */
        .badge-open {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* PROGRESS */
        .badge-progress {
            background: #cce5ff;
            color: #004085;
        }

        /* PENDING */
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        /* CLOSED */
        .badge-closed {
            background: #d4edda;
            color: #155724;
        }

        /* HOVER EFFECT */
        .badge-stat:hover {
            transform: scale(1.1);
            transition: 0.2s;
            cursor: default;
        }
    
        td:first-child, th:first-child {
                text-align: center;
                width: 50px;
            }
    </style>

    <style>
        .chart-container{
            position: relative;
            height: 350px;
        }

        .small-box{
            border-radius: 15px;
            overflow: hidden;
        }

        .small-box .inner h3{
            font-size: 28px;
            font-weight: bold;
        }

        .small-box .inner p{
            font-size: 14px;
        }

        .card{
            border-radius: 12px;
        }

        .card-header{
            font-weight: bold;
        }

        /* MOBILE */
        @media(max-width:768px){

            .content-header h1{
                font-size: 22px;
            }

            .chart-container{
                height: 260px;
            }

            .small-box{
                text-align:center;
            }

            .small-box .icon{
                display:none;
            }

            .card-body{
                padding: 12px;
            }

            table{
                font-size: 12px;
            }

            .badge-stat{
                font-size: 11px;
                padding: 5px 8px;
            }

            .ticket-popup{
                width: 90%;
                right: 5%;
            }
        }
        </style>
@stop