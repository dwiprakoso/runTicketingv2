@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Dashboard Admin</h2>
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Orders by Ticket Category Card -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pesanan per Kategori</h5>
                </div>
                <div class="card-body">
                    @if(empty($categoryStats))
                        <p>Tidak ada data</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryStats as $category => $count)
                                        <tr>
                                            <td>{{ $category ?? 'Tidak ada kategori' }}</td>
                                            <td class="text-end">{{ $count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td class="text-end">{{ array_sum($categoryStats) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Orders by Gender Card -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Pesanan per Gender</h5>
                </div>
                <div class="card-body">
                    @if(empty($genderStats))
                        <p>Tidak ada data</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Gender</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($genderStats as $gender => $count)
                                        <tr>
                                            <td>{{ $gender }}</td>
                                            <td class="text-end">{{ $count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td class="text-end">{{ array_sum($genderStats) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Orders by Shirt Size Card -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Pesanan per Ukuran Baju</h5>
                </div>
                <div class="card-body">
                    @if(empty($sizeStats))
                        <p>Tidak ada data</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Ukuran</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sizeStats as $size => $count)
                                        <tr>
                                            <td>{{ $size }}</td>
                                            <td class="text-end">{{ $count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td class="text-end">{{ array_sum($sizeStats) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
                                        <th>No</th>
                                        <th>ID Pesanan</th>
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
                                        <th>Harga Tiket</th>
                                        <th>Total Diskon</th>
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
                                            <td>{{ $order->order_number }}</td>
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
                                            <td>Rp {{ number_format($order->ticketCategory->price ?? 0, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($order->orderVoucher->voucher->discount_amount ?? 0, 0, ',', '.') }}</td>                                            
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
                        <div class="d-flex justify-content-center mt-3">
                            {{ $orders->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Categories Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Kategori Tiket</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Sisa Kuota</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
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
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm edit-category-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editCategoryModal" 
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-price="{{ $category->price }}"
                                            data-quota="{{ $category->quota }}"
                                            data-description="{{ $category->description }}">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-category-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteCategoryModal"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        Tambah Kategori Baru
                    </button>
                </div>
            </div>
        </div>
                <!-- Voucher Section -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Daftar Voucher</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Voucher</th>
                                <th>Diskon</th>
                                <th>Sisa Kuota</th>
                                <th>Aksi</th>
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
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm edit-voucher-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editVoucherModal" 
                                            data-id="{{ $voucher->id }}"
                                            data-code="{{ $voucher->code }}"
                                            data-discount="{{ $voucher->discount_amount }}"
                                            data-quota="{{ $voucher->quota }}">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-voucher-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteVoucherModal"
                                            data-id="{{ $voucher->id }}"
                                            data-code="{{ $voucher->code }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#createVoucherModal">
                        Tambah Voucher Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal (SINGLE MODAL) -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPrice" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="editPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="editQuota" class="form-label">Kuota</label>
                        <input type="number" class="form-control" id="editQuota" name="quota" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Category Modal (SINGLE MODAL) -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Hapus Kategori Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori "<strong id="deleteCategoryName"></strong>"?</p>
                <p class="text-danger">Perhatian: Kategori dengan pesanan terkait tidak dapat dihapus.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteCategoryForm" action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="quota" class="form-label">Kuota</label>
                        <input type="number" class="form-control" id="quota" name="quota" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Tambah Kategori</button>
                </div>
            </form>
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
                    <div class="mb-3">
                        <label for="ticketCategory" class="form-label">Kategori Tiket</label>
                        <select class="form-select" id="ticketCategory" name="ticket_category_id" required>
                            <option value="">-- Pilih Kategori Tiket --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Voucher hanya berlaku untuk kategori tiket yang dipilih</small>
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
<!-- Edit Voucher Modal -->
<div class="modal fade" id="editVoucherModal" tabindex="-1" aria-labelledby="editVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVoucherModalLabel">Edit Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVoucherForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editVoucherCode" class="form-label">Kode Voucher</label>
                        <input type="text" class="form-control" id="editVoucherCode" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDiscountAmount" class="form-label">Jumlah Diskon (Rp)</label>
                        <input type="number" class="form-control" id="editDiscountAmount" name="discount_amount" 
                            required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="editVoucherQuota" class="form-label">Kuota Voucher</label>
                        <input type="number" class="form-control" id="editVoucherQuota" name="quota" 
                            required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="editTicketCategory" class="form-label">Kategori Tiket</label>
                        <select class="form-select" id="editTicketCategory" name="ticket_category_id" required>
                            <option value="">-- Pilih Kategori Tiket --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Voucher hanya berlaku untuk kategori tiket yang dipilih</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Voucher Modal -->
<div class="modal fade" id="deleteVoucherModal" tabindex="-1" aria-labelledby="deleteVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVoucherModalLabel">Hapus Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus voucher "<strong id="deleteVoucherCode"></strong>"?</p>
                <p class="text-danger">Perhatian: Voucher yang telah digunakan tidak dapat dihapus.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteVoucherForm" action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Verification Modal -->
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
$(document).ready(function() {
    // Verification Modal
    $('#verificationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var orderId = button.data('order-id');
        var userName = button.data('user-name');
        var ticketCategory = button.data('ticket-category');
        var addons = button.data('addons');
        var paymentProof = button.data('payment-proof');
        
        var modal = $(this);
        
        modal.find('#modalUserName').text('Nama Pengguna: ' + userName);
        modal.find('#modalTicketCategory').text('Kategori Tiket: ' + ticketCategory);
        
        var addonsList = modal.find('#modalAddons');
        addonsList.empty();
        
        try {
            if (addons && addons.length > 0) {
                $.each(addons, function(index, addon) {
                    addonsList.append('<li>' + addon.name + ' - ' + addon.price + '</li>');
                });
            } else {
                addonsList.append('<li>Tidak ada add-on</li>');
            }
        } catch (error) {
            console.error("Error menampilkan add-ons:", error);
            addonsList.append('<li>Error menampilkan add-on</li>');
        }
        
        modal.find('#paymentProof').attr('src', paymentProof);
        
        var verifyUrl = "{{ route('admin.orders.verify', ['orderId' => ':orderId']) }}".replace(':orderId', orderId);
        var rejectUrl = "{{ route('admin.orders.reject', ['orderId' => ':orderId']) }}".replace(':orderId', orderId);
        
        modal.find('#verifyForm').attr('action', verifyUrl);
        modal.find('#rejectForm').attr('action', rejectUrl);
        
        modal.find('#order_id').val(orderId);
        modal.find('#reject_order_id').val(orderId);
    });
    
    // Edit Category Button Click
    $('.edit-category-btn').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var price = $(this).data('price');
        var quota = $(this).data('quota');
        var description = $(this).data('description');
        
        var editUrl = "{{ route('admin.categories.update', ':id') }}".replace(':id', id);
        
        $('#editCategoryForm').attr('action', editUrl);
        $('#editName').val(name);
        $('#editPrice').val(price);
        $('#editQuota').val(quota);
        $('#editDescription').val(description);
    });
    
    // Delete Category Button Click
    $('.delete-category-btn').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        var deleteUrl = "{{ route('admin.categories.delete', ':id') }}".replace(':id', id);
        
        $('#deleteCategoryForm').attr('action', deleteUrl);
        $('#deleteCategoryName').text(name);
    });
    
    // Reset forms when modals are closed
    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
        });
        $('.edit-voucher-btn').on('click', function() {
        var id = $(this).data('id');
        var code = $(this).data('code');
        var discount = $(this).data('discount');
        var quota = $(this).data('quota');
        
        var editUrl = "{{ route('admin.vouchers.update', ':id') }}".replace(':id', id);
        
        $('#editVoucherForm').attr('action', editUrl);
        $('#editVoucherCode').val(code);
        $('#editDiscountAmount').val(discount);
        $('#editVoucherQuota').val(quota);
    });

    // Delete Voucher Button Click
    $('.delete-voucher-btn').on('click', function() {
        var id = $(this).data('id');
        var code = $(this).data('code');
        
        var deleteUrl = "{{ route('admin.vouchers.delete', ':id') }}".replace(':id', id);
        
        $('#deleteVoucherForm').attr('action', deleteUrl);
        $('#deleteVoucherCode').text(code);
    });
});
</script>
@endsection