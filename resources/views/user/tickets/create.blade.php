@extends('adminlte::page')

@section('title', 'Create Ticket')

@section('content_header')
    <h1>Create Ticket</h1>
@stop

@section('content')
    <form method="POST" action="/create-ticket" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" placeholder="Nama" required>
        </div>
        <div class="form-group">
            <label>Nomor Meja</label>
            <input type="text" name="nomor_meja" class="form-control" placeholder="Nomor Meja" required>
        </div>
        <div class="form-group">
            <label>Nomor Ruangan</label>
            <input type="text" name="nomor_ruangan" class="form-control" placeholder="Nomor Ruangan" required>
        </div>
        <div class="form-group">
            <label>Nomor WhatsApp</label>
            <input type="text" name="no_whatsapp" class="form-control" placeholder="Nomor WhatsApp" required>
        </div>
        <div class="form-group">
            <label>IP Address</label>
            <input type="text" name="ip_address" class="form-control" placeholder="IP Address Bisa cek melalui aplikasi CheckIP di desktop (optional) ">
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" placeholder="Jelaskan kendala secara detail" required></textarea>
        </div>
        <button type="submit" class="btn btn-success mt-2">Submit Ticket</button>
    </form>
@stop