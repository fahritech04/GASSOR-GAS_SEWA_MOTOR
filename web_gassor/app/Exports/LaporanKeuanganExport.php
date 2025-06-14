<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKeuanganExport implements FromCollection, WithEvents, WithHeadings, WithStyles
{
    protected $user;

    protected $filter;

    protected $tanggal;

    protected $summary = [];

    public function __construct($user, $filter, $tanggal)
    {
        $this->user = $user;
        $this->filter = $filter;
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        $date = Carbon::parse($this->tanggal);
        $query = Transaction::whereHas('motorcycle', function ($q) {
            $q->where('owner_id', $this->user->id);
        });
        if ($this->filter === 'harian') {
            $query->whereDate('start_date', $date);
        } elseif ($this->filter === 'mingguan') {
            $query->whereBetween('start_date', [
                $date->copy()->startOfWeek(),
                $date->copy()->endOfWeek(),
            ]);
        } elseif ($this->filter === 'bulanan') {
            $query->whereMonth('start_date', $date->month)
                ->whereYear('start_date', $date->year);
        }
        $transactions = $query->with(['motorcycle'])->orderBy('start_date', 'desc')->get();
        $this->summary = [
            'total_income' => $transactions->sum('total_amount'),
            'total_transactions' => $transactions->count(),
            'average_income' => $transactions->count() > 0 ? $transactions->sum('total_amount') / $transactions->count() : 0,
        ];

        return $transactions->map(function ($trx) {
            return [
                'Tanggal' => Carbon::parse($trx->start_date)->isoFormat('D MMM YYYY'),
                'Motor' => $trx->motorcycle->name ?? '-',
                'Penyewa' => $trx->name,
                'Harga' => $trx->total_amount,
                'Status Pembayaran' => $trx->payment_status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Motor', 'Penyewa', 'Harga', 'Status Pembayaran'];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'startColor' => ['rgb' => 'e6a43b'],
                'endColor' => ['rgb' => 'ff9d00'],
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        // Border all
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:E'.$highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'e6a43b'],
                ],
            ],
        ]);
        // Format Harga
        $sheet->getStyle('D2:D'.$highestRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        return $sheet;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Auto width
                foreach (range('A', 'E') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
                // Add summary below table
                $row = $event->sheet->getHighestRow() + 2;
                $event->sheet->setCellValue('C'.$row, 'Total Pendapatan:');
                $event->sheet->setCellValue('D'.$row, $this->summary['total_income'] ?? 0);
                $event->sheet->getStyle('C'.$row.':D'.$row)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                ]);
                $event->sheet->getStyle('D'.$row)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
            },
        ];
    }

    public function summary()
    {
        return $this->summary;
    }
}
