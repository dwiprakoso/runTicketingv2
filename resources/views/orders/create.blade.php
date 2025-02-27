@extends('layouts.app')

@section('title', 'Pesan Tiket')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Form Pemesanan -  {{ $category->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                    @csrf
                    <input type="hidden" name="ticket_category_id" value="{{ $category->id }}">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="first_name" class="form-label">Nama Depan</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label for="last_name" class="form-label">Nama Belakang</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if($category->name === 'Fun Run')
                        <div class="mb-3">
                            <label for="jarak_lari" class="form-label">Jarak Lari</label>
                            <select name="jarak_lari" id="jarak_lari" class="form-control">
                                <option value=""><-- Select Option --></option>
                                <option value="3K">3K</option>
                                <option value="7K">7K</option>
                            </select>
                            @error('jarak_lari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value=""><-- Select Option --></option>
                            <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki - Laki</option>
                            <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if($category->name === 'Fun Run' || $category->name === 'Family Run'|| $category->name === 'Early Bird - Fun Run 7K')
                    <div class="mb-3">
                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required>
                        @error('tgl_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    @if($category->name === 'Kids 3K')
                        <div class="mb-3">
                            <label for="tgl_lahir_anak" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tgl_lahir_anak') is-invalid @enderror" id="tgl_lahir_anak" name="tgl_lahir_anak" value="{{ old('tgl_lahir_anak') }}" required>
                            @error('tgl_lahir_anak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">Nomor Whatsapp</label>
                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" required>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="gol_darah" class="form-label">Golongan Darah</label>
                        <select name="gol_darah" id="gol_darah" class="form-control">
                            <option value=""><-- Select Option --></option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                        @error('gol_darah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat') }}" required>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <img src="{{ asset('img/sizeChart.jpg') }}"  width="80%" class="mb-3">
                    <div class="mb-3">
                        <label for="size_chart" class="form-label">Size Chart</label>
                        <select name="size_chart" id="size_chart" class="form-control">
                            <option value=""><-- Select Option --></option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                        @error('size_chart')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="bib_name" class="form-label">Nama BIB</label>
                        <input type="text" class="form-control @error('bib_name') is-invalid @enderror" id="bib_name" name="bib_name" value="{{ old('bib_name') }}" required>
                        @error('bib_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="komunitas" class="form-label">Komunitas</label>
                        <input type="text" class="form-control @error('komunitas') is-invalid @enderror" id="komunitas" name="komunitas" value="{{ old('komunitas') }}">
                        @error('komunitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kontak_darurat_name" class="form-label">Nama Kontak Darurat</label>
                        <input type="text" class="form-control @error('kontak_darurat_name') is-invalid @enderror" id="kontak_darurat_name" name="kontak_darurat_name" value="{{ old('kontak_darurat_name') }}" required>
                        @error('kontak_darurat_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="kontak_darurat_no" class="form-label">Nomor Kontak Darurat</label>
                        <input type="text" class="form-control @error('kontak_darurat_no') is-invalid @enderror" id="kontak_darurat_no" name="kontak_darurat_no" value="{{ old('kontak_darurat_no') }}" required>
                        @error('kontak_darurat_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Form Tambahan Berdasarkan Kategori --}}
                    {{-- Family Run --}}
                    @if($category->name === 'Family Run')
                        <div class="mb-3">
                            <label for="nama_anak" class="form-label">Nama Anak</label>
                            <input type="text" class="form-control @error('nama_anak') is-invalid @enderror" id="nama_anak" name="nama_anak" value="{{ old('nama_anak') }}" required>
                            @error('nama_anak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="usia_anak" class="form-label">Usia Anak</label>
                            <input type="text" class="form-control @error('usia_anak') is-invalid @enderror" id="usia_anak" name="usia_anak" value="{{ old('usia_anak') }}" required>
                            @error('usia_anak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <img src="{{ asset('img/sizeChart.jpg') }}"  width="80%" class="mb-3">
                        <div class="mb-3">
                            <label for="size_anak" class="form-label">Size Chart Anak</label>
                            <select name="size_anak" id="size_anak" class="form-control">
                                <option value=""><-- Select Option --></option>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                            @error('size_anak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="bib_anak" class="form-label">Nama BIB Anak</label>
                            <input type="text" class="form-control @error('bib_anak') is-invalid @enderror" id="bib_anak" name="bib_anak" value="{{ old('bib_anak') }}" required>
                            @error('bib_anak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif 
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('home') }}" class="btn btn-secondary">Kembali</a>
                        <button type="button" class="btn btn-primary" id="verifyDataBtn">Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Data Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h5 class="border-bottom pb-2">Informasi Peserta</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Lengkap:</strong> <span id="verify-nama"></span></p>
                            <p><strong>Gender:</strong> <span id="verify-gender"></span></p>
                            <p><strong>Tanggal Lahir:</strong> <span id="verify-tgl-lahir"></span></p>
                            <p><strong>Email:</strong> <span id="verify-email"></span></p>
                            <p><strong>No. HP:</strong> <span id="verify-no-hp"></span></p>
                            <p><strong>NIK:</strong> <span id="verify-nik"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Golongan Darah:</strong> <span id="verify-gol-darah"></span></p>
                            <p><strong>Alamat:</strong> <span id="verify-alamat"></span></p>
                            <p><strong>Size Baju:</strong> <span id="verify-size-chart"></span></p>
                            <p><strong>Nama BIB:</strong> <span id="verify-bib-name"></span></p>
                            <p><strong>Komunitas:</strong> <span id="verify-komunitas"></span></p>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="border-bottom pb-2">Kontak Darurat</h5>
                    <p><strong>Nama:</strong> <span id="verify-kontak-darurat-name"></span></p>
                    <p><strong>Nomor:</strong> <span id="verify-kontak-darurat-no"></span></p>
                </div>

                <!-- Informasi Kategori Fun Run -->
                @if($category->name === 'Fun Run')
                <div class="mb-3" id="kategori-Fun Run-section">
                    <h5 class="border-bottom pb-2">Informasi Kategori {{ $category->name }}</h5>
                    <p><strong>Jarak Lari:</strong> <span id="verify-jarak-lari"></span></p>
                </div>
                @endif

                <!-- Informasi Kategori Family Run -->
                @if($category->name === 'Family Run')
                <div class="mb-3" id="kategori-family-section">
                    <h5 class="border-bottom pb-2">Informasi Kategori {{ $category->name }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Anak:</strong> <span id="verify-nama-anak"></span></p>
                            <p><strong>Usia Anak:</strong> <span id="verify-usia-anak"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Size Baju Anak:</strong> <span id="verify-size-anak"></span></p>
                            <p><strong>Nama BIB Anak:</strong> <span id="verify-bib-anak"></span></p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mb-3">
                    <h5 class="border-bottom pb-2">Informasi Tiket</h5>
                    <p><strong>Kategori:</strong> {{ $category->name }}</p>
                    <p><strong>Harga:</strong> Rp {{ number_format($category->price, 0, ',', '.') }}</p>
                </div>

                <div class="alert alert-info">
                    <strong>Total Harga:</strong> <span id="verify-total-price">Rp {{ number_format($category->price, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ubah Data</button>
                <button type="button" class="btn btn-primary" id="submitOrderBtn">Konfirmasi & Pesan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const basePrice = {{ $category->price }};
    // validate usia anak
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date(); // Get today's date
        
        // Maximum date (children must be at least 0 years old - use today)
        var maxDate = new Date();
        
        // Minimum date (children must be at most 12 years old)
        var minDate = new Date();
        minDate.setFullYear(today.getFullYear() - 12);
        
        var dateInput = document.getElementById('tgl_lahir_anak');
        
        // Set max and min date attributes (swap them!)
        dateInput.setAttribute('max', maxDate.toISOString().split('T')[0]);
        dateInput.setAttribute('min', minDate.toISOString().split('T')[0]);
    });

    // Format number
    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        });
    }

    // Get gender text
    function getGenderText(gender) {
        return gender === 'laki-laki' ? 'Laki-laki' : (gender === 'perempuan' ? 'Perempuan' : '');
    }

    // Open verification modal
    $('#verifyDataBtn').click(function() {
        // Validate form
        if (!$('#orderForm')[0].checkValidity()) {
            $('#orderForm')[0].reportValidity();
            return;
        }

        // Fill verification modal - Informasi Peserta
        $('#verify-nama').text($('#first_name').val() + ' ' + $('#last_name').val());
        $('#verify-gender').text(getGenderText($('#gender').val()));
        
        // Check which date field to use based on category
        @if($category->name === 'Kids 3K')
            $('#verify-tgl-lahir').text(formatDate($('#tgl_lahir_anak').val()));
        @else
            $('#verify-tgl-lahir').text(formatDate($('#tgl_lahir').val()));
        @endif
        
        $('#verify-email').text($('#email').val());
        $('#verify-no-hp').text($('#no_hp').val());
        $('#verify-nik').text($('#nik').val());
        $('#verify-gol-darah').text($('#gol_darah').val());
        $('#verify-alamat').text($('#alamat').val());
        $('#verify-size-chart').text($('#size_chart').val());
        $('#verify-bib-name').text($('#bib_name').val());
        $('#verify-komunitas').text($('#komunitas').val() || '-');

        // Kontak Darurat
        $('#verify-kontak-darurat-name').text($('#kontak_darurat_name').val());
        $('#verify-kontak-darurat-no').text($('#kontak_darurat_no').val());

        // Kategori spesifik - Fun Run
        @if($category->name === 'Fun Run')
        $('#verify-jarak-lari').text($('#jarak_lari').val());
        @endif

        // Kategori spesifik - Family Run
        @if($category->name === 'Family Run')
        $('#verify-nama-anak').text($('#nama_anak').val());
        $('#verify-usia-anak').text($('#usia_anak').val());
        $('#verify-size-anak').text($('#size_anak').val());
        $('#verify-bib-anak').text($('#bib_anak').val());
        @endif

        // Show the modal
        $('#verificationModal').modal('show');
    });

    // Submit Order
    $('#submitOrderBtn').click(function(e) {
        e.preventDefault(); // Prevent double submission
        
        console.log('Form submission triggered');
        
        // Submit the form
        $('#orderForm').submit();
    });
</script>
@endsection