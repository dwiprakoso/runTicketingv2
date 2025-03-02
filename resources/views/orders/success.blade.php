@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                <h2 class="mt-4">Status Pesanan Anda {{ $order->status }}</h2>
                <p class="mb-4">
                    Terima kasih! Bukti pembayaran Anda telah kami terima dan sedang dalam proses verifikasi. <br>
                    Mohon tunggu konfirmasi melalui email Anda. <br>
                    Verifikasi Pembayaran akan diproses dalam jam kerja (08.00-17.00 WIB).
                </p>
                
                <div class="alert alert-info">
                    <h5 id="order-id" style="display: inline;">
                        <strong>ID Pesanan: {{ $order->order_number }}</strong>
                    </h5>
                    <!-- Ikon salin di sebelah kanan ID Pesanan -->
                    <i class="fas fa-copy" onclick="copyToClipboard()" style="cursor: pointer; margin-left: 10px;"></i>
                    <p class="mb-0"><strong>(Harap Simpan ID Pesanan Anda)</strong></p>
                    <br>
                    <p class="mb-0">Kategori: {{ $order->ticketCategory->name }}</p>
                </div>
                
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyToClipboard() {
        // Ambil elemen dengan ID 'order-id'
        var orderId = document.getElementById("order-id").innerText;
        
        // Buat elemen input sementara untuk menyalin teks
        var tempInput = document.createElement("input");
        document.body.appendChild(tempInput);
        tempInput.value = orderId;
        
        // Pilih dan salin teks
        tempInput.select();
        document.execCommand("copy");
        
        // Hapus elemen input sementara
        document.body.removeChild(tempInput);
        
        // Beri umpan balik kepada pengguna
        alert("ID Pesanan berhasil disalin!");
    }
</script>
@endsection