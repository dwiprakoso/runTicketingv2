
<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Ditolak</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .header { background: #dc3545; color: white; padding: 10px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pesanan Anda Ditolak</h1>
        </div>
        <div class="content">
            <p>Hai <strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong>,</p>
            <p>Pesanan Anda dengan ID <strong>#{{ $order->id }}</strong> telah ditolak.</p>
            <p>Berikut adalah alasan penolakan pesanan:</p>
            <ul>
                <li><strong>Status Pesanan:</strong> {{ ucfirst($order->status) }}</li>
                <li><strong>Kategori Tiket:</strong> {{ $order->ticketCategory->name }}</li>
            </ul>
            <p>Jika Anda memiliki pertanyaan lebih lanjut atau ingin mencoba pemesanan ulang, jangan ragu untuk menghubungi kami.</p>
        </div>
    </div>
</body>
</html>