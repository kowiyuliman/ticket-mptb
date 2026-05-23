@extends('adminlte::page')

@section('title','My Ticket')

@section('content_header')
<h1>My Ticket</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Ticket Saya</h3>
    </div>

    <div class="card-body table-responsive">
        <table id="myTicketTable" class="table table-bordered table-striped">
            <thead class="bg-dark text-white">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th width="100">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><b>{{ $ticket->ticket_code }}</b></td>

                    <td>
                        @if($ticket->status == 'open')
                            <span class="badge bg-info">OPEN</span>
                        @elseif($ticket->status == 'on_progress')
                            <span class="badge bg-primary">PROGRESS</span>
                        @elseif($ticket->status == 'pending')
                            <span class="badge bg-warning">PENDING</span>
                        @elseif($ticket->status == 'closed')
                            <span class="badge bg-success">CLOSED</span>
                        @endif
                    </td>

                    <td>{{ Str::limit($ticket->deskripsi, 50) }}</td>

                    <td>
                        <a href="/admin/ticket/edit/{{ $ticket->id }}" 
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

{{-- CSS DATATABLE --}}
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
        td:first-child, th:first-child {
                    text-align: center;
                    width: 50px;
                }
    </style>
@stop

{{-- JS DATATABLE --}}
@section('js')

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    $('#myTicketTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        language: {
            search: "search:",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Empty",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Empty",
            paginate: {
                previous: "←",
                next: "→"
            }
        }
    });
});
</script>

<script>
    let table = $('#your-table-id').DataTable();
        table.on('order.dt search.dt', function () {
            table.column(0, { search:'applied', order:'applied' })
                .nodes()
                .each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
        }).draw();
</script>

@stop