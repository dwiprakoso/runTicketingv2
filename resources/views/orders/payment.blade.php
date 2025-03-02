@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Pesanan</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5>Informasi Pemesan</h5>
                        <p>
                            <strong>Nama:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}<br>
                            <strong>Email:</strong> {{ $order->user->email }}<br>
                            <strong>No HP:</strong> {{ $order->user->no_hp }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>Detail Tiket</h5>
                        <p>
                            <strong>Kategori:</strong> {{ $order->ticketCategory->name }}<br>
                            <strong>Harga Dasar:</strong> Rp {{ number_format($order->ticketCategory->price, 0, ',', '.') }}<br>
                            <strong>Order ID:</strong> #{{ $order->order_number }}
                        </p>
                    </div>
                </div>
                
                @if($order->addOns->count() > 0)
                <div class="mb-4">
                    <h5>Add-ons</h5>
                    @foreach($order->addOns as $addon)
                    <div class="card mb-2">
                        <div class="card-body py-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="mb-0">
                                        <strong>Nama:</strong> {{ $addon->name }}<br>
                                        <strong>No HP:</strong> {{ $addon->phone }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <p class="mb-0"><strong>Rp {{ number_format($addon->price, 0, ',', '.') }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <!-- Bagian voucher yang diperbaiki -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Kode Voucher</h5>
                                @if($voucher)
                                    <div class="alert alert-success">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-ticket-alt me-2"></i>
                                                <strong>{{ $voucher->code }}</strong> berhasil diterapkan!
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between">
                                            <span>Potongan Harga:</span>
                                            <span class="fw-bold">Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @else
                                    @if(session('error'))
                                        <div class="alert alert-danger mb-3">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <form action="{{ route('orders.apply-voucher', $order->id) }}" method="POST" class="d-flex">
                                        @csrf
                                        <input type="text" name="voucher_code" class="form-control me-2" placeholder="Masukkan kode voucher" autocomplete="off">
                                        <button type="submit" class="btn btn-outline-primary" id="btnApplyVoucher">Terapkan</button>
                                    </form>
                                    <small class="text-muted mt-1">
                                        Masukkan kode voucher untuk mendapatkan potongan harga
                                    </small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5>Batas Waktu Pembayaran</h5>
                                <div class="timer alert alert-warning">
                                    <i class="fas fa-clock me-2"></i>
                                    <span id="countdown" class="fw-bold">00:00:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Metode Pembayaran</h5>
                                @if($order->total_price > 0)
                                <p>Silakan transfer ke rekening berikut:</p>
                                <div class="alert alert-info">
                                    <strong>Bank Danamon</strong><br>
                                    No. Rekening: 903686907118<br>
                                    Atas Nama: Yustisia Dian Advistasari
                                </div>
                                @else
                                <div class="alert alert-success">
                                    <p class="mb-0">GRATIS! Pembayaran tidak diperlukan.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Total Pembayaran</h5>
                                <div class="alert alert-primary">
                                    <h3 class="mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Upload Bukti Pembayaran</h4>
            </div>
            <div class="card-body">
                @if($order->payment && $order->payment->proof_image)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> Bukti pembayaran telah diunggah.
                    </div>
                    <div class="text-end">
                        <a href="{{ route('orders.success', $order->id) }}" class="btn btn-primary">Lihat Tiket</a>
                    </div>
                @elseif($order->total_price <= 0)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> Pembayaran otomatis terverifikasi (GRATIS)
                        @if($voucher)
                            dengan voucher {{ $voucher->code }}
                        @endif
                    </div>
                    
                    @if(!$order->payment)
                    <form id="autoFreeForm" action="{{ route('orders.upload-payment', $order->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_free_voucher" value="1">
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Konfirmasi & Selesai</button>
                        </div>
                    </form>
                    @else
                    <div class="text-end">
                        <a href="{{ route('orders.success', $order->id) }}" class="btn btn-primary">Lihat Tiket</a>
                    </div>
                    @endif
                @else
                    <form action="{{ route('orders.upload-payment', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="proof_image" class="form-label">Bukti Pembayaran (JPG, PNG, PDF max 2MB)</label>
                            <input class="form-control @error('proof_image') is-invalid @enderror" 
                                   type="file" 
                                   id="proof_image" 
                                   name="proof_image" 
                                   accept=".jpg,.jpeg,.png,.pdf" 
                                   required
                                   onchange="validateFileSize(this)">
                            <div class="invalid-feedback" id="file-size-error">File tidak boleh lebih dari 2MB</div>
                            @error('proof_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Upload & Selesai</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
       function validateFileSize(input) {
    // 2MB dalam bytes = 2 * 1024 * 1024
    const maxSize = 2 * 1024 * 1024;
    
    if (input.files && input.files[0]) {
        if (input.files[0].size > maxSize) {
            // File terlalu besar
            input.classList.add('is-invalid');
            document.getElementById('file-size-error').style.display = 'block';
            // Reset input file
            input.value = '';
        } else {
            // File ukurannya sesuai
            input.classList.remove('is-invalid');
            document.getElementById('file-size-error').style.display = 'none';
        }
    }
}
    document.addEventListener("DOMContentLoaded", function () {
        // Voucher form submit handler - prevent double submit
        const voucherForm = document.querySelector('form[action*="apply-voucher"]');
        const applyButton = document.getElementById('btnApplyVoucher');
        
        if (voucherForm) {
            voucherForm.addEventListener('submit', function() {
                // Disable the button and change text
                if (applyButton) {
                    applyButton.disabled = true;
                    applyButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                }
                
                // Store that we've submitted to prevent doubles
                sessionStorage.setItem('voucherSubmitted', 'true');
                
                // Continue with form submission
                return true;
            });
            
            // Check if we've already submitted
            if (sessionStorage.getItem('voucherSubmitted') === 'true') {
                if (applyButton) {
                    applyButton.disabled = true;
                    applyButton.textContent = 'Memproses...';
                }
            }
        }

        // Countdown timer
        const countdownElement = document.getElementById("countdown");
        const countdownDuration = 60 * 60 * 1000; // 1 jam dalam milidetik
        const now = new Date().getTime();
        
        // Cek apakah ada waktu kedaluwarsa yang tersimpan di localStorage
        let deadline = localStorage.getItem("paymentDeadline");

        if (!deadline || now > deadline) {
            // Jika tidak ada deadline atau sudah melewati batas waktu, set ulang
            deadline = now + countdownDuration;
            localStorage.setItem("paymentDeadline", deadline);
        }

        function updateCountdown() {
            const now = new Date().getTime();
            const remainingTime = deadline - now;

            if (remainingTime <= 0) {
                clearInterval(countdownTimer);
                countdownElement.innerHTML = "WAKTU HABIS";
                localStorage.removeItem("paymentDeadline"); // Hapus deadline saat waktu habis
                window.location.href = "{{ route('orders.success', $order->id) }}"; // Redirect ke showSuccess
                return;
            }

            const hours = Math.floor((remainingTime / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((remainingTime / (1000 * 60)) % 60);
            const seconds = Math.floor((remainingTime / 1000) % 60);

            countdownElement.innerHTML =
                (hours < 10 ? "0" + hours : hours) + ":" +
                (minutes < 10 ? "0" + minutes : minutes) + ":" +
                (seconds < 10 ? "0" + seconds : seconds);
        }

        updateCountdown(); // Jalankan sekali agar tidak ada delay 1 detik pertama
        const countdownTimer = setInterval(updateCountdown, 1000);
    });
</script>
@endsection