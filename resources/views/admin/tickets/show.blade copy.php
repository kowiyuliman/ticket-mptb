@extends('adminlte::page')

@section('title', 'My Tickets')

@section('content_header')
    <h1>Tickets Detail</h1>
@stop

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Ticket Code</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Assigned To</th>
            <th>Created At</th>
            <th>Screenshot</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($ticket as $tickets)
        <tr>
            <td>{{ $tickets->ticket_code }}</td>
            <td>{{ ucfirst($tickets->kategori) }}</td>
            <td>
                @if($tickets->status == 'open')
                    <span class="badge bg-info">Open</span>
                @elseif($tickets->status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif($tickets->status == 'on_progress')
                    <span class="badge bg-primary">On Progress</span>
                @elseif($tickets->status == 'closed')
                    <span class="badge bg-success">Closed</span>
                @endif
            </td>
            <td>{{ $tickets->assigned_to ?? '-' }}</td>
            <td>{{ $tickets->created_at->format('d-m-Y H:i') }}</td>
            <td>
                @if($tickets->screenshot)
                <a href="/storage/{{ $ticket->screenshot }}" target="_blank" class="btn btn-sm btn-info">View Screenshot</a>
                @else
                -
                @endif
            </td>
            <td>
                <a href="/admin/ticket/{{$ticket->id}}" 
                    class="btn btn-sm btn-info"><i class="fas fa-eye"></i>
                </a>
                <a href="/admin/ticket/{{$ticket->id}}" 
                    class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="/admin/ticket/delete/{{$ticket->id}}" method="POST" 
                    style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger"
                        onclick="return confirm('Hapus ticket ini ?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Belum ada tiket</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $tickets->links() }} <!-- Pagination -->

div class="alert alert-info">
    Durasi Pengerjaan: <b>{{ $ticket->sla }}</b>
</div>
<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Timeline Progress</h3>
    </div>

    <div class="card-body">

        <ul class="timeline">

            @foreach($ticket->timelines as $timeline)
            <li>
                <i class="fas fa-clock bg-info"></i>

                <div class="timeline-item">
                    <span class="time">
                        <i class="fas fa-clock"></i>
                        {{ $timeline->created_at->format('d-m-Y H:i') }}
                    </span>

                    <h3 class="timeline-header">
                        Status: {{ strtoupper($timeline->status) }}
                    </h3>

                    <div class="timeline-body">
                        {{ $timeline->description }}
                    </div>
                </div>
            </li>
            @endforeach

        </ul>

    </div>
</div>
@stop
