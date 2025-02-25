<!DOCTYPE html>
<html>
<head>
    <title>Tiket PDF</title>
</head>
<body>
    <h1>Tiket Anda</h1>
    <p>Nama: {{ $order->user->name }}</p>
    <p>Kategori Tiket: {{ $order->ticketCategory->name }}</p>
    <p>Harga: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p>Silakan cetak tiket ini atau tunjukkan dalam bentuk digital saat masuk.</p>
</body>
</html>
