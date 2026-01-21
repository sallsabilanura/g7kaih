<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class SiswaTemplateExport implements FromArray, WithHeadings, WithTitle, WithColumnWidths, WithStyles
{
    public function array(): array
    {
        // Mengembalikan array kosong untuk template
        return [
            // Baris contoh data (bisa dikosongkan)
            // ['1234567890', '20240001', 'CONTOH SISWA', '7A', '2010-01-01', 'Jl. Contoh No. 123', 'L'],
            // ['1234567891', '20240002', 'CONTOH SISWI', '7B', '2010-02-01', 'Jl. Contoh No. 456', 'P']
        ];
    }

    public function headings(): array
    {
        return [
            'nisn*',
            'nis*',
            'nama_lengkap*',
            'kelas',
            'tanggal_lahir*',
            'alamat*',
            'jenis_kelamin'
        ];
    }

    public function title(): string
    {
        return 'Template Import Siswa';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // nisn
            'B' => 15, // nis
            'C' => 35, // nama_lengkap
            'D' => 15, // kelas
            'E' => 20, // tanggal_lahir
            'F' => 40, // alamat
            'G' => 15, // jenis_kelamin
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Jumlah baris untuk instruksi
        $lastRow = 15;
        
        // Style untuk header (baris 1)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0033A0']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
            ],
        ]);

        // Menambahkan instruksi pengisian
        $sheet->mergeCells('A3:G12');
        $sheet->setCellValue('A3', 
            "PANDUAN PENGISIAN TEMPLATE SISWA\n\n" .
            "KOLOM YANG WAJIB DIISI (*):\n" .
            "1. nisn      : Nomor Induk Siswa Nasional (unik, 10 digit)\n" .
            "2. nis       : Nomor Induk Sekolah (unik)\n" .
            "3. nama_lengkap : Nama lengkap siswa\n" .
            "4. tanggal_lahir: Format YYYY-MM-DD (contoh: 2010-05-15)\n" .
            "5. alamat    : Alamat lengkap siswa\n\n" .
            "KOLOM OPSIONAL:\n" .
            "6. kelas     : Nama kelas (contoh: 7A, 8B)\n" .
            "7. jenis_kelamin: L (Laki-laki) atau P (Perempuan)\n\n" .
            "CATATAN:\n" .
            "- Mulai isi data dari baris ke-2\n" .
            "- Pastikan NISN dan NIS unik (tidak boleh sama)\n" .
            "- Simpan file dengan format .xlsx atau .xls"
        );

        // Style untuk area instruksi
        $sheet->getStyle('A3:G12')->applyFromArray([
            'font' => [
                'size' => 10,
                'color' => ['rgb' => '333333'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F8F9FA']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);

        // Style untuk contoh data (jika ada)
        if (!empty($this->array())) {
            $dataStartRow = 14; // Setelah instruksi
            $dataEndRow = $dataStartRow + count($this->array()) - 1;
            
            $sheet->mergeCells('A' . ($dataStartRow - 1) . ':G' . ($dataStartRow - 1));
            $sheet->setCellValue('A' . ($dataStartRow - 1), 'CONTOH DATA (HAPUS JIKA TIDAK DIPERLUKAN):');
            
            $sheet->getStyle('A' . ($dataStartRow - 1) . ':G' . ($dataStartRow - 1))->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'DC3545'],
                    'size' => 10,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFEEEE']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        }

        // Border untuk data input (baris 2-100)
        $sheet->getStyle('A2:G100')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'EEEEEE'],
                ],
            ],
        ]);

        // Auto filter untuk header
        $sheet->setAutoFilter('A1:G1');

        // Freeze pane di baris 1 (header tetap terlihat)
        $sheet->freezePane('A2');

        return [
            1 => ['font' => ['bold' => true]],
            'A3:G12' => ['alignment' => ['wrapText' => true]],
        ];
    }
}