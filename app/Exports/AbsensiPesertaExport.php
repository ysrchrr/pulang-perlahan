<?php

namespace App\Exports;

use App\Models\AbsensiPeserta;
use App\Models\Kegiatan;
use App\Models\KegiatanKelas;
use App\Models\V_peserta_terpilih;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiPesertaExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    protected $kelas;
    protected $kegiatan;
    protected $tanggalRange;
    protected $peserta;
    protected $absensiMap;

    public function __construct(KegiatanKelas $kelas, Kegiatan $kegiatan, array $tanggalRange, Collection $peserta, Collection $absensi)
    {
        $this->kelas = $kelas;
        $this->kegiatan = $kegiatan;
        $this->tanggalRange = $tanggalRange;
        $this->peserta = $peserta;
        $this->absensiMap = [];

        foreach ($absensi as $item) {
            $tanggal = $item->tanggal instanceof Carbon
                ? $item->tanggal->format('Y-m-d')
                : Carbon::parse($item->tanggal)->format('Y-m-d');

            $this->absensiMap[$item->id_peserta_terpilih][$tanggal] = $item;
        }
    }

    public function headings(): array
    {
        $headings = [
            'No.',
            'Nama',
        ];

        foreach ($this->tanggalRange as $index => $tanggal) {
            $headings[] = 'Hari ' . ($index + 1) . "\n" . Carbon::parse($tanggal)->format('d-m-Y');
        }

        return $headings;
    }

    public function array(): array
    {
        $rows = [];
        $no = 1;

        foreach ($this->peserta as $item) {
            $row = [
                $no,
                $item->nama_lengkap ?? '-',
            ];

            foreach ($this->tanggalRange as $tanggal) {
                $absensi = $this->absensiMap[$item->id][$tanggal] ?? null;
                $row[] = $absensi && $absensi->presensi == '1' ? 'Hadir' : 'Tidak Hadir';
            }

            $rows[] = $row;
            $no++;
        }

        return $rows;
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 8,
            'B' => 30,
        ];

        $lastColumnIndex = count($this->tanggalRange) + 2;

        for ($i = 3; $i <= $lastColumnIndex; $i++) {
            $widths[Coordinate::stringFromColumnIndex($i)] = 14;
        }

        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = Coordinate::stringFromColumnIndex(count($this->tanggalRange) + 2);
                $lastRow = $sheet->getHighestRow();
                $headerRange = 'A1:' . $lastColumn . '1';
                $tableRange = 'A1:' . $lastColumn . $lastRow;

                $sheet->getStyle($headerRange)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF4B39AC');

                $sheet->getStyle($headerRange)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                $sheet->getRowDimension(1)->setRowHeight(32);

                $sheet->getStyle($tableRange)->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $sheet->getStyle($tableRange)->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('C2:' . $lastColumn . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->freezePane('C2');
                $sheet->setAutoFilter($headerRange);
            },
        ];
    }
}
