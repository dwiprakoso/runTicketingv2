<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $status;

    /**
     * Create a new export instance.
     *
     * @param string|null $status
     * @return void
     */
    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = Order::with(['user', 'ticketCategory', 'orderVoucher.voucher', 'addOns', 'payment']);
        
        // Filter berdasarkan status jika disediakan
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Nama Pemesan',
            'Email',
            'Kategori Tiket',
            'Kode Voucher',
            'Status',
            'Add Ons',
            'Bukti Pembayaran',
            'Tanggal Pemesanan'
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->user->name,
            $order->user->email,
            $order->ticketCategory->name,
            $order->orderVoucher->voucher->code ?? 'Tidak ada',
            $order->status,
            $order->addOns->pluck('name')->implode(', '), // Menggabungkan add-ons
            asset('storage/' . ($order->payment->proof_image ?? '')), // Link bukti pembayaran
            $order->created_at->format('d-m-Y H:i')
        ];
    }
}