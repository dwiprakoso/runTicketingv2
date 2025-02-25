<!DOCTYPE html>
<html>
<head>
    <title>Pengingat Pembayaran</title>
</head>
<body>
    <h1>Halo, {{ $order->user->name }}</h1>
    <p>Ini adalah pengingat untuk menyelesaikan pembayaran pesanan Anda.</p>
    <p>Kategori Tiket: {{ $order->ticketCategory->name }}</p>
    <p>Total Harga: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p>Batas Waktu Pembayaran: {{ $order->payment_deadline }}</p>
    <p>Silakan segera lakukan pembayaran sebelum batas waktu berakhir.</p>
</body>
</html>