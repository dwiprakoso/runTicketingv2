@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                <h2 class="mt-4">Pembayaran Sedang Diproses</h2>
                <p class="mb-4">
                    Terima kasih! Bukti pembayaran Anda telah kami terima dan sedang dalam proses verifikasi. <br>
                    Mohon tunggu konfirmasi melalui email Anda.
                </p>
                
                <div class="alert alert-info">
                    <h5>Order ID: #{{ $order->id }}</h5>
                    <p class="mb-0">Kategori: {{ $order->ticketCategory->name }}</p>
                </div>
                
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
@endsection