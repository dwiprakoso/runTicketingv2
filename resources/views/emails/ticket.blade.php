<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket Event Lari</title>
    <style>
        a{
            color: white;
            text-decoration: none;
        }
        body {
            font-family: 'Montserrat', 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #272727;
            line-height: 1.6;
        }
        .container {
            max-width: 650px;
            margin: 20px auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(45deg, #3a7bd5, #00d2ff);
            padding: 30px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("/api/placeholder/650/200") center/cover;
            opacity: 0.1;
            z-index: 0;
        }
        .header-content {
            position: relative;
            z-index: 1;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            color: rgba(255,255,255,0.9);
            font-weight: 500;
        }
        .ticket-info {
            padding: 30px 25px;
        }
        .ticket-id {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
            border: 2px solid #e5e9f0;
            position: relative;
        }
        .ticket-id:after {
            content: "";
            position: absolute;
            height: 40px;
            width: 40px;
            background-color: #3a7bd5;
            border-radius: 50%;
            top: -15px;
            right: -15px;
            background-image: url("/api/placeholder/20/20");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 20px;
            box-shadow: 0 4px 8px rgba(58, 123, 213, 0.3);
        }
        .ticket-id h2 {
            margin: 0;
            color: #3a7bd5;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .ticket-id p {
            margin: 5px 0 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #272727;
        }
        .participant-info {
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 5px 20px 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .participant-info h3 {
            color: #3a7bd5;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 10px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            align-items: baseline;
        }
        .detail-label {
            width: 40%;
            font-weight: 600;
            color: #6f7285;
            font-size: 14px;
        }
        .detail-value {
            width: 60%;
            font-weight: 500;
            color: #272727;
        }
        .category-badge {
            display: inline-block;
            padding: 6px 15px;
            background-color: #3a7bd5;
            color: white;
            border-radius: 30px;
            font-weight: 600;
            font-size: 13px;
            margin-top: 5px;
            box-shadow: 0 4px 8px rgba(58, 123, 213, 0.2);
        }
        .bib-section {
            background: linear-gradient(135deg, #f6f9fc, #edf1f7);
            padding: 25px 20px;
            text-align: center;
            margin-top: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .bib-section h3 {
            margin-top: 0;
            color: #3a7bd5;
            font-size: 16px;
            font-weight: 600;
        }
        .bib-number {
            font-size: 38px;
            font-weight: 800;
            color: #272727;
            letter-spacing: 3px;
            margin: 10px 0;
            background: -webkit-linear-gradient(45deg, #3a7bd5, #00d2ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .footer {
            background-color: #272727;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            border-top: 5px solid #3a7bd5;
        }
        .qr-code {
            text-align: center;
            margin: 25px 0;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .qr-code-container {
            text-align: center;
        }
        .qr-code img {
            width: 130px;
            height: 130px;
            padding: 5px;
            border: 1px solid #eaeaea;
        }
        .qr-code p {
            margin: 10px 0 0;
            font-size: 14px;
            color: #6f7285;
        }
        .important-note {
            padding: 15px 20px;
            background-color: #fff8ef;
            border-left: 4px solid #ff9800;
            margin: 20px 0;
            font-size: 14px;
            border-radius: 0 8px 8px 0;
            color: #6f4a00;
        }
        .section-divider {
            margin: 30px 0;
            height: 1px;
            background: radial-gradient(ellipse at center, #eaeaea 0%, rgba(255,255,255,0) 70%);
            position: relative;
        }
        .section-divider:before {
            content: "●";
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            color: #3a7bd5;
            font-size: 16px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                width: auto;
            }
            .header h1 {
                font-size: 24px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label, .detail-value {
                width: 100%;
            }
            .detail-value {
                margin-top: 2px;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <h1>Semarang Apoteker Run 2025</h1>
                <p>Minggu, 15 Juni 2025 | Lakers, BSB City Semarang</p>
            </div>
        </div>
        
        <div class="ticket-info">
            <div class="ticket-id">
                <h2>ID PESANAN</h2>
                <p>{{ $order->order_number }}</p>
            </div>
            
            <div class="participant-info">
                <h3>INFORMASI PESERTA</h3>
                <div class="detail-row">
                    <div class="detail-label">Nama</div>
                    <div class="detail-value">{{ $order->user->first_name }} {{ $order->user->last_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Gender</div>
                    <div class="detail-value">{{ ucfirst($order->user->gender) }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tanggal Lahir</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($order->user->tgl_lahir)->locale('id')->isoFormat('D MMMM Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $order->user->email }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">No Whatsapp</div>
                    <div class="detail-value">{{ $order->user->no_hp }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">NIK</div>
                    <div class="detail-value">{{ $order->user->nik }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Golongan Darah</div>
                    <div class="detail-value">{{ $order->user->gol_darah ?? '-' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Alamat</div>
                    <div class="detail-value">{{ $order->user->alamat }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Size Baju</div>
                    <div class="detail-value">{{ $order->size_chart }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nama BIB</div>
                    <div class="detail-value">{{ $order->bib_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Komunitas</div>
                    <div class="detail-value">{{ $order->user->komunitas ?? '-' }}</div>
                </div>
            </div>
            
            <div class="section-divider"></div>
            
            <div class="participant-info">
                <h3>KONTAK DARURAT</h3>
                <div class="detail-row">
                    <div class="detail-label">Nama</div>
                    <div class="detail-value">{{ $order->user->kontak_darurat_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nomor</div>
                    <div class="detail-value">{{ $order->user->kontak_darurat_no }}</div>
                </div>
            </div>
            
            <div class="section-divider"></div>
            
            
            <div class="important-note">
                <strong>Penting:</strong> Harap membawa e-ticket ini beserta identitas diri (KTP) saat pengambilan race pack. Pengambilan race pack tidak dapat diwakilkan.
            </div>
        </div>
        
        <div class="footer">
            <p>&copy;2025 Ticketify.id | <a href="https://wa.me/6282227031735">Telp: +62 822-2703-1735</a> | Email: ticketifyid@gmail.com</p>
        </div>
    </div>
</body>
</html>