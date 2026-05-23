@extends('adminlte::page')

@section('title','User Management')

@section('content_header')
<h3>User Management</h3>
@stop

@section('content')


<div class="card mb-3">
    <div class="card-body">
     @if(auth()->user()->role == 'admin')
        <div class="mb-3">
            <button class="btn btn-secondary btn-filter" data-role="all">
                <i class="fas fa-users"></i> All
            </button>
            <button class="btn btn-danger btn-filter" data-role="admin">
                <i class="fas fa-user-shield"></i> Admin
            </button>
            <button class="btn btn-primary btn-filter" data-role="leader">
                <i class="fas fa-user-tie"></i> Leader
            </button>
            <button class="btn btn-success btn-filter" data-role="user">
                <i class="fas fa-user"></i> User
            </button>
        </div>
    @endif

        <a href="/admin/users/create" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah User
        </a>


        @if(auth()->user()->role == 'admin')
            <form action="/admin/users/import" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success">
                            <i class="fas fa-upload"></i> Import Excel
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>


<table id="table-user" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
            <th>Leader</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>
                    <span class="badge bg-info">{{ $user->role }}</span>
                </td>
                <td>{{ $user->leader->name ?? '-' }}</td>
                <td>
                    <a href="/admin/users/edit/{{ $user->id }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="/admin/users/delete/{{ $user->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus user?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop


@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
        td:first-child, th:first-child {
                    text-align: center;
                    width: 50px;
                }
    </style>

<style>
        .btn-filter.active {
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        transform: scale(1.05);
    }
</style>
@stop


@section('js')

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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

<script>
    $(document).ready(function() {

        let table = $('#table-user').DataTable({
            responsive: true,
            autoWidth: false
        });

        // FILTER ROLE
        $('.btn-filter').click(function() {

            let role = $(this).data('role');

            if(role == 'all'){
                table.column(3).search('').draw(); // reset filter
            } else {
                table.column(3).search(role).draw(); // filter role
            }

            // UI aktif button
            $('.btn-filter').removeClass('active');
            $(this).addClass('active');
        });

        // nomor urut tetap rapi
        table.on('order.dt search.dt', function () {
            table.column(0, { search:'applied', order:'applied' })
                .nodes()
                .each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
        }).draw(); 

    });
</script>
@stop