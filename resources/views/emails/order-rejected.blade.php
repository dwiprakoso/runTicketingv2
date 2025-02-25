<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Ditolak</title>
</head>
<body>
    <h1>Pesanan Anda Ditolak</h1>
    <p>Hai {{ $order->user->name }},</p>
    <p>Pesanan Anda dengan ID #{{ $order->id }} telah ditolak.</p>
    <p>Berikut adalah alasan penolakan pesanan:</p>
    <ul>
        <li><strong>Status Pesanan:</strong> {{ $order->status }}</li>
        <li><strong>Kategori Tiket:</strong> {{ $order->ticketCategory->name }}</li>
    </ul>
    <p>Jika Anda memiliki pertanyaan lebih lanjut atau ingin mencoba pemesanan ulang, jangan ragu untuk menghubungi kami.</p>
</body>
</html>
