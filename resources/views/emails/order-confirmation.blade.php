<!DOCTYPE html>
<html>
<head>
    <title>Pemesanan Ditolak</title>
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
            <h1>Pemesanan Ditolak</h1>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong></p>
            <p>Mohon maaf, pesanan Anda dengan ID <strong>{{ $order->id }}</strong> telah ditolak.</p>
            <p>Jika ada pertanyaan lebih lanjut, silakan hubungi layanan pelanggan kami.</p>
        </div>
    </div>
</body>
</html>
