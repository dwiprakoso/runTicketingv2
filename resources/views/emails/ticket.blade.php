<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Tiket Anda</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .header { background: #007bff; color: #fff; padding: 10px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
        .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Konfirmasi Tiket Anda</h1>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong></p>
            <p>Terima kasih telah melakukan pemesanan. Berikut adalah detail tiket Anda:</p>
            <ul>
                <li><strong>Kategori Tiket:</strong> {{ $order->ticketCategory->name }}</li>
                <li><strong>Harga Tiket:</strong> Rp {{ number_format($order->ticketCategory->price ?? 0, 0, ',', '.') }}</li>
                <li><strong>Total Diskon:</strong> Rp {{ number_format($order->orderVoucher->voucher->discount_amount ?? 0, 0, ',', '.') }}</li>
                <li><strong>Total Biaya:</strong> Rp {{ number_format($order->payment->amount ?? 0, 0, ',', '.') }}</li>
                <li><strong>Voucher:</strong> {{ $order->orderVoucher->voucher->code ?? 'Tidak ada' }}</li>
                <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
            </ul>
            <p>Silakan tunjukkan email ini saat masuk.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Event Organizer. Semua Hak Dilindungi.</p>
        </div>
    </div>
</body>
</html>