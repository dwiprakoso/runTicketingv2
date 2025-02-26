<!DOCTYPE html>
<html>
<head>
    <title>Tiket PDF</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .ticket { border: 2px solid #333; padding: 20px; max-width: 600px; margin: auto; border-radius: 10px; }
        .ticket-header { background: #007bff; color: white; text-align: center; padding: 10px; border-radius: 10px 10px 0 0; }
        .ticket-content { padding: 20px; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h2>Tiket Anda</h2>
        </div>
        <div class="ticket-content">
            <p><strong>Nama:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Kategori Tiket:</strong> {{ $order->ticketCategory->name }}</p>
            <p><strong>Harga Tiket:</strong> Rp {{ number_format($order->ticketCategory->price ?? 0, 0, ',', '.') }}</p>
            <p><strong>Total Diskon:</strong> Rp {{ number_format($order->orderVoucher->voucher->discount_amount ?? 0, 0, ',', '.') }}</p>
            <p><strong>Total Biaya:</strong> Rp {{ number_format($order->payment->amount ?? 0, 0, ',', '.') }}</p>
            <p><strong>Voucher:</strong> {{ $order->orderVoucher->voucher->code ?? 'Tidak ada' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p>Silakan cetak tiket ini atau tunjukkan dalam bentuk digital saat masuk.</p>
        </div>
    </div>
</body>
</html>