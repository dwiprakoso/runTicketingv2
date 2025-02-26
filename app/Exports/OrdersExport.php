<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = Order::with(['user', 'ticketCategory', 'orderVoucher.voucher', 'addOns', 'payment']);
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Email',
            'No. HP',
            'NIK',
            'Gender',
            'Tgl Lahir',
            'Gol. Darah',
            'Alamat',
            'Size Baju',
            'Nama BIB',
            'Komunitas',
            'Kontak Darurat',
            'Jarak Lari',
            'Nama Anak',
            'Usia Anak',
            'Size Baju Anak',
            'Nama BIB Anak',
            'Kategori Tiket',
            'Harga Tiket',
            'Total Diskon',
            'Total Biaya',
            'Voucher',
            'Status',
            'Tanggal Pemesanan'
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->user->first_name . ' ' . $order->user->last_name,
            $order->user->email,
            $order->user->no_hp,
            $order->user->nik,
            ucfirst($order->user->gender),
            optional($order->user->tgl_lahir)->format('d/m/Y'),
            $order->user->gol_darah ?? '-',
            $order->user->alamat,
            $order->size_chart,
            $order->bib_name,
            $order->user->komunitas ?? '-',
            $order->user->kontak_darurat_name . ' - ' . $order->user->kontak_darurat_no,
            $order->jarak_lari ?? '-',
            $order->nama_anak ?? '-',
            $order->usia_anak ?? '-',
            $order->size_anak ?? '-',
            $order->bib_anak ?? '-',
            $order->ticketCategory->name,
            'Rp ' . number_format($order->ticketCategory->price ?? 0, 0, ',', '.'),
            'Rp ' . number_format($order->orderVoucher->voucher->discount_amount ?? 0, 0, ',', '.'),
            'Rp ' . number_format($order->payment->amount ?? 0, 0, ',', '.'),
            $order->orderVoucher->voucher->code ?? 'Tidak ada',
            ucfirst($order->status),
            $order->created_at->format('d-m-Y')
        ];
    }
}
