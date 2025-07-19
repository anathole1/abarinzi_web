<?php
namespace App\Exports;
use App\Models\Contribution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ContributionsExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected Collection $contributions;
    public function __construct(Collection $contributions) { $this->contributions = $contributions; }
    public function collection() { return $this->contributions; }

    public function headings(): array {
        return [
            'ID', 'Member Name', 'Member Email', 'Type', 'Amount (RWF)', 'Payment Method', 'Transaction ID',
            'Status', 'Payment Date', 'Approved By', 'Submitted Date'
        ];
    }

    public function map($contribution): array {
        return [
            $contribution->id,
            $contribution->user->name ?? 'N/A',
            $contribution->user->email ?? 'N/A',
            Str::title(str_replace('_', ' ', $contribution->type)),
            $contribution->amount,
            Str::title(str_replace('_', ' ', $contribution->payment_method ?? '')),
            $contribution->transaction_id ?? 'N/A',
            ucfirst($contribution->status),
            $contribution->payment_date ? $contribution->payment_date->format('Y-m-d') : 'N/A',
            $contribution->approver->name ?? 'N/A',
            $contribution->created_at->format('Y-m-d H:i'),
        ];
    }

    public function registerEvents(): array {
        return [ AfterSheet::class => function(AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();
            $sheet->insertNewRowBefore(1, 3);
            $lastColumn = $sheet->getHighestDataColumn();
            $sheet->mergeCells('A1:'.$lastColumn.'1');
            $sheet->setCellValue('A1', config('app.name') . ' - Contributions Report');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('A2:'.$lastColumn.'2');
            $sheet->setCellValue('A2', 'Generated on: ' . now()->format('F j, Y, g:i a'));
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $headingsRange = 'A4:'.$lastColumn.'4';
            $sheet->getStyle($headingsRange)->getFont()->setBold(true);
            $sheet->getStyle($headingsRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE0E0E0');
            $sheet->freezePane('A5');
        },];
    }
}