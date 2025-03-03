<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

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
            'No',
            'ID Pesanan',
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
            'Bukti Pembayaran',
            'Tanggal Pemesanan'
        ];
    }

    public function map($order): array
    {
        // Format tanggal lahir dengan Carbon seperti di tampilan dashboard
        $tglLahir = $order->user->tgl_lahir ? Carbon::parse($order->user->tgl_lahir)->locale('id')->isoFormat('D MMMM Y') : '-';
        
        // Format tanggal lahir anak
        $tglLahirAnak = isset($order->user->tgl_lahir_anak) ? Carbon::parse($order->user->tgl_lahir_anak)->locale('id')->isoFormat('D MMMM Y') : '-';
        
        // Format kontak darurat sesuai tampilan di dashboard (nama dan nomor di baris terpisah)
        $kontakDarurat = $order->user->kontak_darurat_name . "\n" . $order->user->kontak_darurat_no;
        
        // URL untuk bukti pembayaran
        $buktiPembayaran = isset($order->payment->proof_image) ? 
            url('storage/' . $order->payment->proof_image) : 
            'Tidak ada bukti';

        return [
            $order->id,
            $order->order_number,
            $order->user->first_name . ' ' . $order->user->last_name,
            $order->user->email,
            $order->user->no_hp,
            $order->user->nik,
            ucfirst($order->user->gender),
            $tglLahir,
            $order->user->gol_darah ?? '-',
            $order->user->alamat,
            $order->size_chart,
            $order->bib_name,
            $order->user->komunitas ?? '-',
            $kontakDarurat,
            $order->jarak_lari ?? '-',
            $order->nama_anak ?? '-',
            $tglLahirAnak,
            $order->size_anak ?? '-',
            $order->bib_anak ?? '-',
            $order->ticketCategory->name,
            'Rp ' . number_format($order->ticketCategory->price ?? 0, 0, ',', '.'),
            'Rp ' . number_format($order->orderVoucher->voucher->discount_amount ?? 0, 0, ',', '.'),
            'Rp ' . number_format($order->payment->amount ?? 0, 0, ',', '.'),
            isset($order->orderVoucher->voucher) ? $order->orderVoucher->voucher->code : 'Tidak ada voucher',
            ucfirst($order->status),
            $buktiPembayaran,
            $order->created_at->format('d-m-Y H:i:s')
        ];
    }
}