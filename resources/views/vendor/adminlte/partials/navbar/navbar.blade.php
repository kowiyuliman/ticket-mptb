@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right') 

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif

        {{-- Notifikasi --}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                    @php
                        $count = auth()->user()->unreadNotifications()->count();
                    @endphp
                    @if($count > 0)
                        <span class="badge badge-danger navbar-badge">
                            {{ $count > 99 ? '99+' : $count }}
                        </span>
                    @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notification-dropdown">
                <div class="dropdown-header">
                    <strong>
                        {{ $count }}
                        Notifikasi Belum Dibaca
                    </strong>
                </div>
                @foreach(
                    auth()->user()
                        ->unreadNotifications()
                        ->latest()
                        ->take(5)
                        ->get()
                    as $notification
                )

                   <a href="/notification/{{$notification->id}}"
                    class="dropdown-item notification-item">
                        <div class="font-weight-bold">
                            {{ $notification->data['message'] }}
                        </div>
                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </a>
                @endforeach
                <!-- <a href="#"
                    class="dropdown-item text-center text-primary"
                    data-toggle="modal"
                    data-target="#notifModal">
                        <i class="fas fa-list"></i>
                        Lihat Semua Notifikasi

                    </a> -->
                <div class="dropdown-divider"></div>
                    <div class="px-2 py-2">
                        <a href="/notifications/read"
                        class="btn btn-success btn-sm btn-block mb-2">
                            <i class="fas fa-check"></i>
                                Tandai Semua Dibaca
                        </a>
                        <form action="/notifications/clear"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                class="btn btn-danger btn-sm btn-block">
                                <i class="fas fa-trash"></i>
                                Hapus Semua
                            </button>
                        </form>
                    </div>
        </li>
    </ul>

<!-- MODAL NOTIFIKASI -->
<div class="modal fade"
     id="notifModal"
     tabindex="-1"
     role="dialog">
    <div class="modal-dialog modal-lg"
         role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="far fa-bell"></i>
                    Semua Notifikasi
                    <span class="badge badge-light">
                        {{ auth()->user()->notifications()->count() }}
                    </span>
                </h5>
                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pesan</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(auth()->user()
                                ->notifications()
                                ->latest()
                                ->take(10)
                                ->get() as $notif)
                                <tr>
                                    <td>
                                        {{ $notif->data['message'] }}
                                    </td>
                                    <td>
                                        {{ $notif->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        @if(isset($notif->data['url']))
                                            <a href="{{ $notif->data['url'] }}"
                                               class="btn btn-sm btn-primary">
                                                Buka
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="text-center">
                                        Tidak ada notifikasi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a href="/notifications/read"
                   class="btn btn-success">
                    <i class="fas fa-check"></i>
                    Tandai Semua Dibaca
                </a>
                <form action="/notifications/clear"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Hapus Semua
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</nav>


@section('CSS')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    .notification-dropdown{
        width:400px;
        max-height:450px;
        overflow-y:auto;
    }

    .notification-item{
        white-space:normal;
        line-height:1.4;
    }
    .ticket-toast{
        position:fixed;
        top:20px;
        right:20px;
        width:320px;
        background:white;
        border-left:5px solid #28a745;
        border-radius:12px;
        padding:15px;
        box-shadow:
            0 10px 25px rgba(0,0,0,.15);
        z-index:999999;
        cursor:pointer;
        animation:slideIn .3s ease;
    }

    .ticket-toast-title{
        font-weight:bold;
        margin-bottom:5px;
    }

    .ticket-toast-body{
        font-size:13px;
    }

    .modal-body{
        max-height: 550px;
        overflow-y: auto;
    }
</style>

@stop

@section('JS')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
    function showNotification(message, url) {
        const notif = document.createElement('div');
        notif.className = 'ticket-toast';
        notif.innerHTML = `
            <div class="ticket-toast-title">
                Ticket Baru
            </div>
            <div class="ticket-toast-body">
                ${message}
            </div>
        `;

        notif.onclick = () => {
            window.location = url;
        };

        document.body.appendChild(notif);

        setTimeout(() => {
            notif.remove();
        }, 5000);
    }
</script>

@stop
