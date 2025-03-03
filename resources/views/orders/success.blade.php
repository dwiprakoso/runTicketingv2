@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center py-5">
                @if($order->status == 'pending')
                    <i class="fas fa-clock text-warning" style="font-size: 5rem;"></i>
                    <h2 class="mt-4">Status Pesanan Anda Sedang Diproses</h2>
                    <p class="mb-4">
                        Terima kasih! Bukti pembayaran Anda telah kami terima dan sedang dalam proses verifikasi. <br>
                        Mohon tunggu konfirmasi melalui email Anda. <br>
                        Verifikasi Pembayaran akan diproses dalam jam kerja (08.00-17.00 WIB).
                    </p>
                @elseif($order->status == 'verified')
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    <h2 class="mt-4">Status Pesanan Anda Sudah Diverifikasi</h2>
                    <p class="mb-4">
                        Terima kasih! Bukti pembayaran Anda telah berhasil diverifikasi. <br>
                        Anda akan menerima konfirmasi lebih lanjut melalui email.
                    </p>
                @else
                    <i class="fas fa-times-circle text-danger" style="font-size: 5rem;"></i>
                    <h2 class="mt-4">Status Pesanan Anda Tidak Diterima</h2>
                    <p class="mb-4">
                        Mohon maaf, pesanan Anda telah Tidak Diterima. Silakan hubungi customer service untuk informasi lebih lanjut atau periksa alasan penolakan melalui email.
                    </p>
                @endif
                
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