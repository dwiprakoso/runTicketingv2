<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Tidak Diterima</title>
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(45deg, #dc3545, #ff6b6b);
            color: white;
            padding: 25px 15px;
            text-align: center;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 30px 25px;
        }
        .order-details {
            background-color: #f9f9f9;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .order-details h2 {
            font-size: 18px;
            margin-top: 0;
            color: #dc3545;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .order-details ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .order-details li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .order-details li:last-child {
            border-bottom: none;
        }
        .order-details strong {
            color: #444;
        }
        .message {
            margin-bottom: 20px;
            line-height: 1.7;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #666;
            border-top: 1px solid #eee;
        }
        .contact-us {
            background-color: #fff8f8;
            border-radius: 12px;
            padding: 15px 20px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pesanan Anda Tidak Diterima</h1>
        </div>
        <div class="content">
            <div class="message">
                <p>Hai <strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong>,</p>
                <p>Kami mohon maaf untuk memberitahukan bahwa pesanan Anda dengan ID <strong>{{ $order->order_number }}</strong> telah gagal untuk verifikasi pembayaran.</p>
            </div>
            
            <div class="order-details">
                <h2>Detail Pesanan</h2>
                <ul>
                    <li><strong>ID Pesanan:</strong> {{ $order->order_number }}</li>
                    <li><strong>Tanggal Pemesanan:</strong> {{ \Carbon\Carbon::parse($order->created_at)->locale('id')->isoFormat('D MMMM Y') }}</li>
                    <li><strong>Status Pesanan:</strong> Tidak Diterima</li>
                    <li><strong>Kategori Tiket:</strong> Semarang Apoteker Run 2025 - {{ $order->ticketCategory->name }}</li>
                    <li><strong>Harga:</strong> Rp {{ number_format($order->payment->amount ?? 0, 0, ',', '.') }}</li>
                </ul>
            </div>
            
            <div class="contact-us">
                <p><strong>Butuh bantuan?</strong> Jika Anda memiliki pertanyaan atau ingin mencoba pemesanan ulang, tim kami siap membantu. Silakan hubungi kami melalui:</p>
                <p>- WhatsApp: <a href="https://wa.me/6282227031735"><strong>+62 822-2703-1735</strong></a><br>
                - Email: <strong>ticketifyid@gmail.com</strong></p>
                <p>Mohon sertakan ID pesanan Anda untuk mempercepat proses.</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2025 Ticketify.id | Platform Ticketing Terpercaya</p>
        </div>
    </div>
</body>
</html>