<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        return Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with(['items.menu', 'booking.user'])
            ->where('status', 'paid')
            ->get()
            ->map(function($o) {
                return [
                    'Kode Order'  => $o->order_code,
                    'Tipe'        => ucfirst($o->type),
                    'Kamar'       => $o->booking ? 'Kamar ' . $o->booking->room->room_number : 'Walk-in',
                    'Tamu'        => $o->booking ? $o->booking->user->name : 'Tamu Umum',
                    'Jumlah Item' => $o->items->count(),
                    'Total'       => $o->total_price,
                    'Pembayaran'  => strtoupper($o->payment_method ?? '-'),
                    'Status'      => ucfirst($o->status),
                    'Tanggal'     => $o->created_at->format('d/m/Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Order', 'Tipe', 'Kamar', 'Tamu',
            'Jumlah Item', 'Total (Rp)', 'Pembayaran',
            'Status', 'Tanggal'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1A1A2E']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Order Restoran';
    }
}