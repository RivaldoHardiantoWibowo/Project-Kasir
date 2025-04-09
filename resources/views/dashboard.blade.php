@extends('main')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@if (Auth::user()->role == 'admin')
<div class="container mt-5">
    <h1 class="text-center mb-4">Selamat Datang, Administrator!</h1>

    <div class="row">
        <!-- Bar Chart -->
        <div class="col-md-8">
            <div class="card p-4 shadow-sm">
                <h5 class="text-center">Jumlah Penjualan</h5>
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart + Legend -->
        <div class="col-md-4">
            <div class="card p-4 shadow-sm">
                <h5 class="text-center">Persentase Penjualan Produk</h5>
                <canvas id="pieChart"></canvas>
                <div id="legend" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
@endif

@if (Auth::user()->role == 'kasir')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h4 class="mb-3">Selamat Datang, Petugas!</h4>
            <div class="card bg-light p-4">
                <h6 class="text-muted">Total Penjualan Hari Ini</h6>
                <h2 class="fw-bold">{{ $count }}</h2>
                <p class="text-muted">Jumlah total penjualan yang terjadi hari ini.</p>
                <p class="text-muted small">
                    @if ( $updated && $updated->created_at)
                    Terakhir diperbarui: {{ $updated->created_at->format('d-m-Y H:i:s') }}
                    @else
                    Tidak ada Transaksi Hari ini
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
