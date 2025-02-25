@extends('layouts.app')

@section('title', 'Event Tickets - Beranda')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="card-title">Semarang Apoteker Run</h2>
                        <p class="card-text">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae, illo ipsa dolorem eligendi aut nam dolores, facilis possimus nemo, natus recusandae minus repellendus voluptates harum cumque sit libero fugit at. Labore asperiores magnam cum iusto exercitationem et atque neque facilis hic, voluptas obcaecati doloribus assumenda consequatur consectetur? Est nesciunt atque iste dicta hic magnam placeat illo asperiores sint. Esse tempora laboriosam commodi perferendis quae, iusto explicabo molestias aspernatur facilis officia voluptas nihil quas tempore blanditiis modi harum nisi quam, quos praesentium adipisci id, culpa nulla saepe. Blanditiis assumenda mollitia porro aliquam ipsum et vero cum, culpa exercitationem ipsa facilis maiores!
                        </p>
                        <p class="card-text">
                            <i class="fas fa-calendar-alt me-2"></i> Tanggal: To Be Announced<br>
                            <i class="fas fa-map-marker-alt me-2"></i> Lokasi: To Be Announced
                        </p>
                    </div>
                    <div class="col-md-4">
                        <img src="{{ asset('img/banner.jpg') }}" alt="Event Banner" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h3 class="mb-4">Pilih Kategori Tiket</h3>

<div class="row">
    @foreach($categories as $category)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $category->name }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Rp {{ number_format($category->price, 0, ',', '.') }}</h6>
                <p class="card-text">{{ $category->description ?? 'Nikmati event dengan tiket kategori ini.' }}</p>
                {{-- <p class="mb-3"><span class="badge bg-info">Sisa Kuota: {{ $category->availableQuota() }}</span></p> --}}
                
                @if($category->availableQuota() > 0)
                    <a href="{{ route('orders.create', $category->id) }}" class="btn btn-primary">Pesan Sekarang</a>
                @else
                    <button class="btn btn-secondary" disabled>Tiket Habis</button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection