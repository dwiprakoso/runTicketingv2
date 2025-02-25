<!DOCTYPE html>
<html>
<head>
    <title>Tiket Anda</title>
</head>
<body>
    <h1>Halo, {{ $order->user->name }}</h1>
    <p>Berikut adalah tiket Anda:</p>
    <p>Kategori Tiket: {{ $order->ticketCategory->name }}</p>
    <p>Total Harga: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p>Silakan tunjukkan email ini saat masuk.</p>
</body>
</html>