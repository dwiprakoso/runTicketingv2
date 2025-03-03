<!DOCTYPE html>
<html>
<head>
    <title>Pengingat Pembayaran</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #ff9e2c 0%, #ffcc00 100%);
            color: #333;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .content {
            padding: 30px;
        }
        .order-details {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-details ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .order-details li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .order-details li:last-child {
            border-bottom: none;
        }
        .btn {
            display: inline-block;
            background-color: #ff9e2c;
            color: #fff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #ffcc00;
        }
        .btn-container {
            text-align: center;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .important-note {
            background-color: #fff8e1;
            border-left: 4px solid #ffcc00;
            padding: 10px 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PENGINGAT PEMBAYARAN</h1>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong>,</p>
            
            <p>Terima kasih telah mendaftar untuk event kami. Ini adalah pengingat untuk menyelesaikan pembayaran pesanan Anda.</p>
            
            <div class="order-details">
                <ul>
                    <li><strong>Nomor Order:</strong> {{ $order->order_number }}</li>
                    <li><strong>Kategori Tiket:</strong> {{ $order->ticketCategory->name }}</li>
                    <li><strong>Total Harga:</strong> Rp {{ number_format($order->payment->amount ?? 0, 0, ',', '.') }}</li>
                </ul>
            </div>

            <div class="important-note">
                <p>Silakan lakukan pembayaran ke rekening berikut:</p>
                <p><strong>Bank Danamon</strong><br>
                No. Rekening: 903686907118<br>
                Atas Nama: Kyky Herlyanti atau Yustisia Dian Advistasari</p>
            </div>
            
            <div class="btn-container">
                <a href="{{ route('orders.payment', $order->id, true) }}" class="btn">UPLOAD BUKTI PEMBAYARAN</a>
            </div>
            
            <p>Setelah melakukan pembayaran, silakan klik tombol di atas untuk mengunggah bukti pembayaran Anda. Tim kami akan memverifikasi pembayaran Anda secepatnya.</p>
            
            <p>Untuk informasi lebih lanjut, silakan hubungi kami di <a href="mailto:ticketifyid@gmail.com">ticketifyid@gmail.com</a> atau WhatsApp di <a href="https://wa.me/6282227031735">Telp: +62 822-2703-1735</a>.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Ticketify. All rights reserved.</p>
        </div>
    </div>
</body>
</html>