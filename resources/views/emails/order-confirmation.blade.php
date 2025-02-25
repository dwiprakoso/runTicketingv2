<!DOCTYPE html>
<html>
<head>
    <title>Pemesanan Ditolak</title>
</head>
<body>
    <h1>Halo, {{ $order->user->name }}</h1>
    <p>Mohon maaf, pesanan Anda dengan ID <strong>{{ $order->id }}</strong> telah ditolak.</p>
    <p>Jika ada pertanyaan lebih lanjut, silakan hubungi layanan pelanggan kami.</p>
</body>
</html>