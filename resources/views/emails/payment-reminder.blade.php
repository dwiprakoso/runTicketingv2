<!DOCTYPE html>
<html>
<head>
    <title>Pengingat Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .header { background: #ffcc00; color: #333; padding: 10px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pengingat Pembayaran</h1>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong></p>
            <p>Ini adalah pengingat untuk menyelesaikan pembayaran pesanan Anda.</p>
            <ul>
                <li><strong>Kategori Tiket:</strong> {{ $order->ticketCategory->name }}</li>
                <li><strong>Total Harga:</strong> Rp {{ number_format($order->payment->amount ?? 0, 0, ',', '.') }}</li>
                <li><strong>Batas Waktu Pembayaran:</strong> {{ $order->payment_deadline }}</li>
            </ul>
            <p>Silakan segera lakukan pembayaran sebelum batas waktu berakhir.</p>
        </div>
    </div>
</body>
</html>