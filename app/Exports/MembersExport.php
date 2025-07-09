<?php

namespace App\Exports;

use App\Models\MemberProfile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet; // For type hinting
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing; // Import the Drawing class

class MembersExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    ShouldAutoSize
{
    protected Collection $members;
    protected string $reportTitle;
    protected string $generatedDate;
    protected string $appName;
    protected string $logoPath; // Add property for logo path

    public function __construct(Collection $members)
    {
        $this->members = $members;
        $this->appName = config('app.name', 'EFOTEC Alumni Association');
        $this->reportTitle = $this->appName . " - Members Report";
        $this->generatedDate = "Generated on: " . now()->format('F j, Y, g:i a');
        // IMPORTANT: Ensure your logo is in the public path or accessible by the server
        $this->logoPath = public_path('images/your-logo.png'); // Adjust path to your logo
    }

    public function collection()
    {
        return $this->members;
    }

    public function headings(): array
    {
        return [ /* ... your headings ... */
            'Account No', 'First Name', 'Last Name', 'Profile Email', 'User Account Email',
            'Phone Number', 'National ID', 'Membership Category', 'Membership Status',
            'Date Joined Association', 'Year Left EFOTEC', 'Current Location',
        ];
    }

    public function map($memberProfile): array
    {
        return [ /* ... your mapping ... */
            $memberProfile->accountNo ?? 'N/A',
            $memberProfile->first_name,
            $memberProfile->last_name,
            $memberProfile->email,
            $memberProfile->user->email ?? 'N/A',
            $memberProfile->phone_number,
            $memberProfile->national_id,
            $memberProfile->memberCategory->name ?? 'N/A',
            ucfirst($memberProfile->status),
            $memberProfile->dateJoined ? $memberProfile->dateJoined->format('Y-m-d') : 'N/A',
            $memberProfile->year_left_efotec ?: 'N/A',
            $memberProfile->current_location ?: 'N/A',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet->getDelegate();

                // --- Header Setup ---
                $numberOfHeaderRows = 5; // Increase for logo + title + date + empty row
                $sheet->insertNewRowBefore(1, $numberOfHeaderRows); // Insert rows for header

                // 1. Add Logo
                if (file_exists($this->logoPath)) {
                    $drawing = new Drawing();
                    $drawing->setName($this->appName . ' Logo');
                    $drawing->setDescription($this->appName . ' Logo');
                    $drawing->setPath($this->logoPath);
                    $drawing->setHeight(50); // Adjust height as needed
                    $drawing->setCoordinates('A1'); // Place logo in cell A1
                    // $drawing->setOffsetX(10); // Optional offset within the cell
                    // $drawing->setOffsetY(10);
                    $drawing->setWorksheet($sheet);

                    // You might need to adjust row height for the logo
                    $sheet->getRowDimension(1)->setRowHeight(40); // Adjust row height (in points)
                }

                // Determine the last column letter based on your headings
                $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->headings()));

                // 2. Add Report Title (e.g., starting from column B or C to make space for logo, or merge over logo)
                // Option A: Title next to logo (if logo is small)
                // $titleCellStart = 'B1'; // If logo is in A1
                // Option B: Title below logo or merged across more cells
                $titleRow = 2; // Put title on the second inserted row
                $titleRange = 'A'.$titleRow.':'.$lastColumnLetter.$titleRow;
                $sheet->mergeCells($titleRange);
                $sheet->setCellValue('A'.$titleRow, $this->reportTitle);
                $sheet->getStyle('A'.$titleRow)->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A'.$titleRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                                                             ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension($titleRow)->setRowHeight(25);


                // 3. Add Generated Date (below title)
                $dateRow = 3;
                $dateRange = 'A'.$dateRow.':'.$lastColumnLetter.$dateRow;
                $sheet->mergeCells($dateRange);
                $sheet->setCellValue('A'.$dateRow, $this->generatedDate);
                $sheet->getStyle('A'.$dateRow)->getFont()->setSize(10);
                $sheet->getStyle('A'.$dateRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                                                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension($dateRow)->setRowHeight(15);


                // Row 4 is left empty for spacing

                // 5. Style the actual data headings (which are now on row $numberOfHeaderRows + 1, e.g., row 6)
                $headingsStartRow = $numberOfHeaderRows + 1;
                $headingsRange = 'A'.$headingsStartRow.':'.$lastColumnLetter.$headingsStartRow;
                $sheet->getStyle($headingsRange)->getFont()->setBold(true);
                $sheet->getStyle($headingsRange)->getFill()
                      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFE0E0E0');
                $sheet->getRowDimension($headingsStartRow)->setRowHeight(20);

                // Freeze the header rows (logo, title, date, empty, headings)
                $sheet->freezePane('A' . ($headingsStartRow + 1));
            },
        ];
    }
}