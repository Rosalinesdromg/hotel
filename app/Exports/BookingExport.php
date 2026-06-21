<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
        return Booking::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with(['user', 'room.roomType'])
            ->get()
            ->map(function($b) {
                return [
                    'Kode Booking'   => $b->booking_code,
                    'Tamu'           => $b->user->name,
                    'Email'          => $b->user->email,
                    'Kamar'          => $b->room->room_number,
                    'Tipe'           => $b->room->roomType->name,
                    'Paket'          => ucwords(str_replace('_', ' ', $b->package)),
                    'Check-in'       => $b->check_in->format('d/m/Y'),
                    'Check-out'      => $b->check_out->format('d/m/Y'),
                    'Malam'          => $b->check_in->diffInDays($b->check_out),
                    'Total'          => $b->total_price,
                    'DP Dibayar'     => $b->dp_amount,
                    'Status Bayar'   => strtoupper($b->payment_status),
                    'Status Booking' => ucfirst($b->status),
                    'Tanggal Dibuat' => $b->created_at->format('d/m/Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Booking', 'Tamu', 'Email', 'Kamar', 'Tipe Kamar',
            'Paket', 'Check-in', 'Check-out', 'Malam',
            'Total (Rp)', 'DP Dibayar (Rp)', 'Status Bayar',
            'Status Booking', 'Tanggal Dibuat'
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
        return 'Data Booking';
    }
}