@extends('main')
@section('title', 'Penjualan')
@section('breadcrumb', 'Penjualan')
@section('page-title', 'Penjualan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-primary">Export Penjualan (.xlsx)</button>
        </div>
        @if (Auth::user()->role == 'kasir')
        <div>
            <a class="btn btn-success" href="{{ route('pembelians.create') }}">Tambah Penjualan</a>
        </div>
        @endif
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="dropdown me-2">
            Tampilkan
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                10
            </button>
            Entri
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">10</a></li>
                <li><a class="dropdown-item" href="#">15</a></li>
                <li><a class="dropdown-item" href="#">20</a></li>
            </ul>
        </div>
        <div>
            <form method="GET">
                <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
            </form>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col" class="text-center">Nama Pelanggan</th>
                <th scope="col" class="text-center">Tanggal Penjualan</th>
                <th scope="col" class="text-center">Total Harga</th>
                <th scope="col" class="text-center">Dibuat Oleh</th>
                <th scope="col"  class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction as $item)
            @php
                $id = 1;
                $id++
            @endphp
            <tr>
                <th scope="row" class="text-center">{{ $id }}</th>
                <td class="text-center">
                    @if ($item->member)
                    {{ $item->member->name }}
                    @else
                        Non-Member
                    @endif
                </td>
                <td class="text-center">{{ $item->created_at->format('Y M d') }}</td>
                <td class="text-center">{{ $item->total_price}}</td>
                <td class="text-center">{{ $item->user->name }}</td>
                <td class="text-center">
                    <div class="d-grid gap-4 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-warning">Lihat</button>
                        <button class="btn btn-primary" type="button">Unduh Bukti</button>
                    </div>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            Menampilkan 1 hingga 10 dari 100 entri
        </div>
        <div>
            <nav aria-label="Page navigation example" >
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
              </nav>
        </div>
    </div>
</div>
@endsection
