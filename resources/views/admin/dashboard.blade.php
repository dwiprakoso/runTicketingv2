@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Dashboard Admin</h2>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h3 class="mb-3">Semua Pesanan</h3>
                    <a href="{{ route('export.orders') }}" class="btn btn-primary mb-1">
                        Export Data Pemesan
                    </a>
                </div>
                
                <div class="card-body">
                    @if($orders->isEmpty())
                        <p>Tidak ada pesanan.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>No. HP</th>
                                        <th>NIK</th>
                                        <th>Gender</th>
                                        <th>Tgl Lahir</th>
                                        <th>Gol. Darah</th>
                                        <th>Alamat</th>
                                        <th>Size Baju</th>
                                        <th>Nama BIB</th>
                                        <th>Komunitas</th>
                                        <th>Kontak Darurat</th>
                                        <th>Jarak Lari</th>
                                        <th>Nama Anak</th>
                                        <th>Usia Anak</th>
                                        <th>Size Baju Anak</th>
                                        <th>Nama BIB Anak</th>
                                        <th>Kategori Tiket</th>
                                        <th>Total Biaya</th>
                                        <th>Voucher</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                                            <td>{{ $order->user->email }}</td>
                                            <td>{{ $order->user->no_hp }}</td>
                                            <td>{{ $order->user->nik }}</td>
                                            <td>{{ ucfirst($order->user->gender) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->user->tgl_lahir)->format('d/m/Y') }}</td>
                                            <td>{{ $order->user->gol_darah ?? '-' }}</td>
                                            <td>{{ $order->user->alamat }}</td>
                                            <td>{{ $order->size_chart }}</td>
                                            <td>{{ $order->bib_name }}</td>
                                            <td>{{ $order->user->komunitas ?? '-' }}</td>
                                            <td>
                                                {{ $order->user->kontak_darurat_name }}<br>
                                                {{ $order->user->kontak_darurat_no }}
                                            </td>
                                            <td>{{ $order->jarak_lari ?? '-' }}</td>
                                            <td>{{ $order->nama_anak }}</td>
                                            <td>{{ $order->usia_anak }}</td>
                                            <td>{{ $order->size_anak }}</td>
                                            <td>{{ $order->bib_anak }}</td>
                                            <td>{{ $order->ticketCategory->name }}</td>
                                            <td>Rp {{ number_format($order->payment->amount ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                @if($order->orderVoucher && $order->orderVoucher->voucher)
                                                    <span class="badge bg-info">
                                                        {{ $order->orderVoucher->voucher->code }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Tidak ada voucher</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($order->status == 'pending') 
                                                        bg-warning 
                                                    @elseif($order->status == 'verified') 
                                                        bg-success 
                                                    @else 
                                                        bg-danger 
                                                    @endif">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($order->status == 'pending')
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#verificationModal" 
                                                        data-order-id="{{ $order->id }}" 
                                                        data-user-name="{{ $order->user->first_name }} {{ $order->user->last_name }}" 
                                                        data-ticket-category="{{ $order->ticketCategory->name }}" 
                                                        data-addons="{{ json_encode($order->addOns) }}" 
                                                        data-payment-proof="{{ asset('storage/' . $order->payment->proof_image) }}">
                                                        Verifikasi
                                                    </button>
                                                @else
                                                    <p>Tidak Ada Aksi</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Categories Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Kategori Tiket</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                {{-- <th>Deskripsi</th> --}}
                                <th>Harga</th>
                                <th>Sisa Kuota</th>
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    {{-- <td>{{ $category->description }}</td> --}}
                                    <td>Rp {{ number_format($category->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($category->availableQuota() <= 0) 
                                                bg-danger 
                                            @elseif($category->availableQuota() <= 10) 
                                                bg-warning 
                                            @else 
                                                bg-success 
                                            @endif">
                                            {{ $category->availableQuota() }}
                                        </span>
                                    </td>
                                    {{-- <td>
                                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-primary btn-sm">Perbarui</button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Voucher Section -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Voucher</h5>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createVoucherModal">
                        Tambah Voucher Baru
                    </button>
                </div>
                <div class="card-body">
                    @if($vouchers->isEmpty())
                        <p>Tidak ada voucher.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Voucher</th>
                                    <th>Diskon</th>
                                    <th>Sisa Kuota</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vouchers as $voucher)
                                    <tr>
                                        <td>{{ $voucher->code }}</td>
                                        <td>Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($voucher->availableQuota() <= 0) 
                                                    bg-danger 
                                                @elseif($voucher->availableQuota() <= 5) 
                                                    bg-warning 
                                                @else 
                                                    bg-success 
                                                @endif">
                                                {{ $voucher->availableQuota() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Tambah Voucher Baru -->
        <div class="modal fade" id="createVoucherModal" tabindex="-1" aria-labelledby="createVoucherModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createVoucherModalLabel">Tambah Voucher Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.vouchers.create') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="voucherCode" class="form-label">Kode Voucher</label>
                                <input type="text" class="form-control" id="voucherCode" name="code" required 
                                    placeholder="Masukkan kode voucher (unik)">
                            </div>
                            <div class="mb-3">
                                <label for="discountAmount" class="form-label">Jumlah Diskon (Rp)</label>
                                <input type="number" class="form-control" id="discountAmount" name="discount_amount" 
                                    required min="0" placeholder="Masukkan jumlah diskon">
                            </div>
                            <div class="mb-3">
                                <label for="voucherQuota" class="form-label">Kuota Voucher</label>
                                <input type="number" class="form-control" id="voucherQuota" name="quota" 
                                    required min="1" placeholder="Masukkan jumlah kuota voucher">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Voucher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verification Modal (ADDED CONTENT HERE) -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationModalLabel">Verifikasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Detail Pesanan -->
                    <div class="col-md-6">
                        <h5>Detail Pesanan</h5>
                        <hr>
                        <p id="modalUserName">Nama Pengguna: </p>
                        <p id="modalTicketCategory">Kategori Tiket: </p>
                        <h6>Add Ons:</h6>
                        <ul id="modalAddons">
                            <!-- Will be filled by JavaScript -->
                        </ul>
                    </div>
                    
                    <!-- Bukti Pembayaran -->
                    <div class="col-md-6">
                        <h5>Bukti Pembayaran</h5>
                        <hr>
                        <div class="text-center">
                            <img id="paymentProof" src="" alt="Bukti Pembayaran" class="img-fluid mb-3" style="max-height: 300px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Form untuk menolak pembayaran -->
                <form id="rejectForm" action="" method="POST" style="margin-right: auto;">
                    @csrf
                    <input type="hidden" id="reject_order_id" name="order_id">
                    <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                </form>
                
                <!-- Form untuk memverifikasi pembayaran -->
                <form id="verifyForm" action="" method="POST">
                    @csrf
                    <input type="hidden" id="order_id" name="order_id">
                    <button type="submit" class="btn btn-success">Verifikasi Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('verificationModal').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Tombol yang diklik
        var orderId = button.getAttribute('data-order-id'); // ID Pesanan
        var userName = button.getAttribute('data-user-name');
        var ticketCategory = button.getAttribute('data-ticket-category');
        var addons = JSON.parse(button.getAttribute('data-addons')); // Parsing JSON untuk Add On
        var paymentProof = button.getAttribute('data-payment-proof'); // URL bukti pembayaran

        // Mengisi konten modal
        document.getElementById('modalUserName').textContent = 'Nama Pengguna: ' + userName;
        document.getElementById('modalTicketCategory').textContent = 'Kategori Tiket: ' + ticketCategory;

        // Kosongkan list dan menambahkan Add-ons
        var addonsList = document.getElementById('modalAddons');
        addonsList.innerHTML = ''; // Kosongkan list sebelumnya
        try {
            if (addons && addons.length > 0) {
                addons.forEach(function (addon) {
                    var li = document.createElement('li');
                    li.textContent = addon.name + ' - ' + addon.price;
                    addonsList.appendChild(li);
                });
            } else {
                var li = document.createElement('li');
                li.textContent = 'Tidak ada add-on';
                addonsList.appendChild(li);
            }
        } catch (error) {
            console.error("Error menampilkan add-ons:", error);
            var li = document.createElement('li');
            li.textContent = 'Error menampilkan add-on';
            addonsList.appendChild(li);
        }

        // Set bukti pembayaran
        document.getElementById('paymentProof').src = paymentProof;
        
        // Set URL untuk form action
        var verifyUrl = "{{ route('admin.orders.verify', ['orderId' => ':orderId']) }}".replace(':orderId', orderId);
        var rejectUrl = "{{ route('admin.orders.reject', ['orderId' => ':orderId']) }}".replace(':orderId', orderId);
        
        // Set form action untuk verifikasi dan tolak pembayaran
        document.getElementById('verifyForm').action = verifyUrl;
        document.getElementById('rejectForm').action = rejectUrl;
        
        // Set input order_id untuk form
        document.getElementById('order_id').value = orderId;
        document.getElementById('reject_order_id').value = orderId;
    });
</script>
@endsection