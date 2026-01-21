<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use ZipArchive;
use Validator;

class SiswaController extends Controller
{
    /**
     * Tampilkan daftar semua siswa.
     */
    public function index()
    {
        $siswas = Siswa::with('kelas.guruWali')->paginate(10);
        $kelasList = Kelas::all();
        
        return view('siswas.index', compact('siswas', 'kelasList'));
    }

    /**
     * Tampilkan form untuk menambah siswa.
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('siswas.create', compact('kelas'));
    }

    /**
     * Simpan data siswa baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:siswas,nisn|max:20',
            'nis' => 'required|string|unique:siswas,nis|max:20',
            'nama_lengkap' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:500',
        ]);

        Siswa::create($validated);

        return redirect()->route('siswas.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Preview QR Code di Browser
     */
    public function showBarcode(Siswa $siswa)
    {
        // Generate QR Code dengan nama yang lebih spesifik
        $fileName = 'qrcode-' . $siswa->nis . '-' . str_replace(' ', '-', strtolower($siswa->nama_lengkap)) . '.png';
        
        $qrCode = QrCode::create($siswa->nis)
            ->setSize(250)
            ->setMargin(10)
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        $qrCodeBase64 = base64_encode($result->getString());

        return view('siswas.show-barcode', compact('siswa', 'qrCodeBase64', 'fileName'));
    }

    /**
     * Download QR Code NIS Siswa (Single) dengan nama lengkap
     */
    public function downloadBarcode(Siswa $siswa)
    {
        // Format nama file: qrcode-nis-nama-siswa.png
        $fileName = 'qrcode-' . $siswa->nis . '-' . str_replace(' ', '-', strtolower($siswa->nama_lengkap)) . '.png';
        
        $qrCode = QrCode::create($siswa->nis)
            ->setSize(300)
            ->setMargin(10)
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return Response::make($result->getString(), 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    /**
     * Download QR Code Card dengan Info Siswa
     */
    public function downloadBarcodeCard(Siswa $siswa)
    {
        $fileName = 'kartu-qrcode-' . $siswa->nis . '-' . str_replace(' ', '-', strtolower($siswa->nama_lengkap)) . '.html';
        
        $qrCode = QrCode::create($siswa->nis)
            ->setSize(200)
            ->setMargin(5)
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        $qrCodeBase64 = base64_encode($result->getString());

        // Generate HTML untuk kartu
        $html = view('siswas.barcode-card', compact('siswa', 'qrCodeBase64'))->render();

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    /**
     * Download All QR Codes (Bulk)
     */
    public function downloadAllBarcodes()
    {
        $siswas = Siswa::all();

        $zip = new ZipArchive();
        $zipFileName = 'qrcodes-siswa-' . date('Y-m-d-His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($siswas as $siswa) {
                $qrCode = QrCode::create($siswa->nis)
                    ->setSize(300)
                    ->setMargin(10)
                    ->setErrorCorrectionLevel(ErrorCorrectionLevel::High);
                
                $writer = new PngWriter();
                $result = $writer->write($qrCode);
                
                // Format nama file: qrcode-nis-nama-siswa.png
                $fileName = 'qrcode-' . $siswa->nis . '-' . str_replace(' ', '-', strtolower($siswa->nama_lengkap)) . '.png';
                $zip->addFromString($fileName, $result->getString());
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Import data siswa dari Excel.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Hapus header row
            array_shift($rows);

            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                try {
                    // Skip row kosong
                    if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
                        continue;
                    }

                    // Cari kelas berdasarkan nama
                    $kelas = Kelas::where('nama_kelas', $row[3])->first();
                    
                    // Validasi jika kelas tidak ditemukan
                    if (!$kelas) {
                        $errors[] = "Baris " . ($index + 2) . ": Kelas '" . $row[3] . "' tidak ditemukan";
                        continue;
                    }

                    // Validasi data
                    $validator = Validator::make([
                        'nisn' => $row[0],
                        'nis' => $row[1],
                        'nama_lengkap' => $row[2],
                        'tanggal_lahir' => $row[4],
                        'alamat' => $row[5],
                    ], [
                        'nisn' => 'required|unique:siswas,nisn',
                        'nis' => 'required|unique:siswas,nis',
                        'nama_lengkap' => 'required',
                        'tanggal_lahir' => 'required|date',
                        'alamat' => 'required',
                    ]);

                    if ($validator->fails()) {
                        $errors[] = "Baris " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                        continue;
                    }

                    // Create siswa
                    Siswa::create([
                        'nisn' => $row[0],
                        'nis' => $row[1],
                        'nama_lengkap' => $row[2],
                        'kelas_id' => $kelas->id,
                        'tanggal_lahir' => $row[4],
                        'alamat' => $row[5],
                    ]);

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Berhasil mengimport {$imported} data siswa.";
            if (!empty($errors)) {
                $message .= " Terdapat " . count($errors) . " error: " . implode('; ', array_slice($errors, 0, 5));
            }

            return redirect()->route('siswas.index')->with(
                !empty($errors) ? 'warning' : 'success',
                $message
            );

        } catch (\Exception $e) {
            return redirect()->route('siswas.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        // Buat spreadsheet manual
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'nisn');
        $sheet->setCellValue('B1', 'nis');
        $sheet->setCellValue('C1', 'nama_lengkap');
        $sheet->setCellValue('D1', 'kelas');
        $sheet->setCellValue('E1', 'tanggal_lahir');
        $sheet->setCellValue('F1', 'alamat');

        // Set contoh data
        $sheet->setCellValue('A2', '1234567890');
        $sheet->setCellValue('B2', '2024001');
        $sheet->setCellValue('C2', 'John Doe');
        $sheet->setCellValue('D2', '10 IPA 1');
        $sheet->setCellValue('E2', '2005-05-15');
        $sheet->setCellValue('F2', 'Jl. Contoh Alamat No. 123');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'E6E6FA']
            ]
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Catatan di sheet
        $sheet->setCellValue('H1', 'CATATAN:');
        $sheet->setCellValue('H2', '1. Semua kolom wajib diisi');
        $sheet->setCellValue('H3', '2. Kolom "kelas" harus sesuai dengan nama kelas yang ada di sistem');
        $sheet->setCellValue('H4', '3. Format tanggal: YYYY-MM-DD');

        // Save file
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $fileName = 'template-import-siswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Tampilkan form edit siswa.
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('siswas.edit', compact('siswa', 'kelas'));
    }

    /**
     * Update data siswa.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('siswas')->ignore($siswa->id)
            ],
            'nis' => [
                'required',
                'string',
                'max:20',
                Rule::unique('siswas')->ignore($siswa->id)
            ],
            'nama_lengkap' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:500',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswas.index')->with('success', 'Siswa berhasil diperbarui!');
    }

    /**
     * Hapus siswa.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('siswas.index')->with('success', 'Siswa berhasil dihapus!');
    }

    /**
     * Tampilkan detail siswa.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load('kelas');
        return view('siswas.show', compact('siswa'));
    }
}