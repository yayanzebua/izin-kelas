<?php

namespace App\Exports;

use App\Models\Permission;
use App\Models\ClassRoom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class LecturerReportExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithStyles, WithColumnWidths, ShouldAutoSize, WithColumnFormatting, WithCustomStartCell, WithEvents
{
    protected $filters;
    protected $rowIndex = 5;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function collection()
    {
        $query = Permission::with(['user', 'classRoom']);

        if (isset($this->filters['subject_id']) && $this->filters['subject_id']) {
            $query->where('class_room_id', $this->filters['subject_id']);
        }

        if (isset($this->filters['start_date']) && $this->filters['start_date']) {
            $query->whereDate('date', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date']) && $this->filters['end_date']) {
            $query->whereDate('date', '<=', $this->filters['end_date']);
        }

        if (isset($this->filters['type']) && $this->filters['type']) {
            $query->where('type', $this->filters['type']);
        }

        return $query->latest('date')->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA MAHASISWA',
            'NIM',
            'MATA KULIAH',
            'TANGGAL IZIN',
            'JENIS',
            'ALASAN / KETERANGAN',
            'PREVIEW GAMBAR',
            'DOWNLOAD GAMBAR',
            'DOWNLOAD PDF',
        ];
    }

    public function map($permission): array
    {
        $this->rowIndex++;
        
        $pdfLink = '';
        if ($permission->file && strpos($permission->file, '.pdf') !== false) {
            $pdfLink = url('storage/' . $permission->file);
        }

        $imageLink = '';
        if ($permission->file && (strpos($permission->file, '.jpg') !== false || strpos($permission->file, '.png') !== false || strpos($permission->file, '.jpeg') !== false)) {
            $imageLink = url('storage/' . $permission->file);
        }

        return [
            $this->rowIndex - 5,
            strtoupper($permission->user->name),
            $permission->user->nim,
            strtoupper($permission->classRoom->name),
            \Carbon\Carbon::parse($permission->date)->format('d/m/Y'),
            strtoupper($permission->type),
            $permission->description,
            '', 
            $imageLink,
            $pdfLink,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => '0', 
            'I' => NumberFormat::FORMAT_TEXT, 
            'J' => NumberFormat::FORMAT_TEXT, 
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $permissions = $this->collection();
        
        foreach ($permissions as $index => $permission) {
            if ($permission->file && (strpos($permission->file, '.jpg') !== false || strpos($permission->file, '.png') !== false || strpos($permission->file, '.jpeg') !== false)) {
                $path = storage_path('app/public/' . $permission->file);
                if (file_exists($path)) {
                    $drawing = new Drawing();
                    $drawing->setName('Bukti');
                    $drawing->setPath($path);
                    
                    // Constrain both width and height to fit cell perfectly
                    $drawing->setHeight(130);
                    $drawing->setWidth(160); 
                    
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(10);
                    $drawing->setCoordinates('H' . ($index + 6));
                    $drawings[] = $drawing;
                }
            }
        }

        return $drawings;
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = $this->collection()->count();
        $sheet->getStyle('A5:J' . ($rowCount + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:J' . ($rowCount + 5))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        $sheet->getStyle('A5:J5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E40AF']],
            'alignment' => ['wrapText' => true],
        ]);

        $sheet->getRowDimension(5)->setRowHeight(35);

        for ($i = 6; $i <= $rowCount + 5; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(150); // Increased height for images
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':J' . $i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');
            }
            $sheet->getStyle('A' . $i . ':J' . $i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFCBD5E1');
            $sheet->getStyle('G' . $i)->getAlignment()->setWrapText(true);
            
            $this->applyHyperlink($sheet, 'I' . $i);
            $this->applyHyperlink($sheet, 'J' . $i);
        }
    }

    private function applyHyperlink($sheet, $cell)
    {
        $value = $sheet->getCell($cell)->getValue();
        if ($value) {
            $sheet->getCell($cell)->getHyperlink()->setUrl($value);
            $sheet->getStyle($cell)->getFont()->setColor(new Color(Color::COLOR_BLUE))->setUnderline(true);
            $sheet->setCellValue($cell, 'DOWNLOAD');
        } else {
            $sheet->setCellValue($cell, '-');
            $sheet->getStyle($cell)->getFont()->setColor(new Color('FF94A3B8'));
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $subjectName = 'SEMUA MATA KULIAH';
                if (isset($this->filters['subject_id']) && $this->filters['subject_id']) {
                    $sub = ClassRoom::find($this->filters['subject_id']);
                    if ($sub) $subjectName = strtoupper($sub->name);
                }

                // Title
                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI IZIN MAHASISWA');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 20, 'color' => ['argb' => 'FF1E3A8A']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                $sheet->getRowDimension(1)->setRowHeight(50);

                // Better Header Alignment (labels in Col A, values in Col B-J merged)
                $sheet->setCellValue('A2', 'MATA KULIAH');
                $sheet->setCellValue('B2', ': ' . $subjectName);
                
                $sheet->setCellValue('A3', 'KELAS');
                $classCode = \App\Models\Setting::get('class_code', '07TPLE018');
                $sheet->setCellValue('B3', ': ' . $classCode);
                
                $sheet->setCellValue('A4', 'WAKTU');
                $sheet->setCellValue('B4', ': ' . strtoupper(date('d F Y | H:i')) . ' WIB');

                $sheet->getStyle('A2:A4')->getFont()->setBold(true);
                $sheet->getStyle('B2:B4')->getFont()->setBold(true);
                
                $sheet->getRowDimension(2)->setRowHeight(25);
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(4)->setRowHeight(25);
            },
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Wider for label
            'B' => 35,
            'C' => 20,
            'D' => 25,
            'E' => 15,
            'F' => 12,
            'G' => 45,
            'H' => 30, // Preview Image
            'I' => 22,
            'J' => 22,
        ];
    }
}
