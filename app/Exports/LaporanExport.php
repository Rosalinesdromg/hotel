<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class LaporanExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function sheets(): array
    {
        return [
            new BookingExport($this->startDate, $this->endDate),
            new OrderExport($this->startDate, $this->endDate),
        ];
    }
}