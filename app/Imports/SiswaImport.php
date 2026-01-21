<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SiswaImport implements ToCollection, WithHeadingRow
{
    private $errors = [];
    private $successCount = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena heading row + 1 baris offset
            
            // Skip baris kosong
            if (empty($row['nisn']) && empty($row['nis']) && empty($row['nama_lengkap'])) {
                continue;
            }

            try {
                // Validasi data
                $validator = Validator::make($row->toArray(), [
                    'nisn' => [
                        'required',
                        'unique:siswas,nisn',
                        'digits:10'
                    ],
                    'nis' => [
                        'required',
                        'unique:siswas,nis'
                    ],
                    'nama_lengkap' => 'required|string|max:255',
                    'kelas' => 'nullable|string',
                    'tanggal_lahir' => 'required|date_format:Y-m-d',
                    'alamat' => 'required|string',
                    'jenis_kelamin' => 'nullable|in:L,P',
                ], [
                    'nisn.required' => 'NISN wajib diisi',
                    'nisn.unique' => 'NISN sudah terdaftar',
                    'nisn.digits' => 'NISN harus 10 digit',
                    'nis.required' => 'NIS wajib diisi',
                    'nis.unique' => 'NIS sudah terdaftar',
                    'nama_lengkap.required' => 'Nama lengkap wajib diisi',
                    'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
                    'tanggal_lahir.date_format' => 'Format tanggal harus YYYY-MM-DD',
                    'alamat.required' => 'Alamat wajib diisi',
                ]);

                if ($validator->fails()) {
                    $this->errors[] = "Baris {$rowNumber}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Cari atau buat kelas
                $kelasId = null;
                if (!empty($row['kelas'])) {
                    $kelas = Kelas::firstOrCreate(
                        ['nama_kelas' => $row['kelas']],
                        ['nama_kelas' => $row['kelas']]
                    );
                    $kelasId = $kelas->id;
                }

                // Buat siswa baru
                Siswa::create([
                    'nisn' => $row['nisn'],
                    'nis' => $row['nis'],
                    'nama_lengkap' => $row['nama_lengkap'],
                    'kelas_id' => $kelasId,
                    'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', $row['tanggal_lahir']),
                    'alamat' => $row['alamat'],
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                ]);

                $this->successCount++;

            } catch (\Exception $e) {
                $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
            }
        }

        // Jika ada error, throw exception dengan detail error
        if (!empty($this->errors)) {
            $errorMessage = "Import gagal pada baris berikut:\n\n" . implode("\n", $this->errors);
            if ($this->successCount > 0) {
                $errorMessage .= "\n\n" . $this->successCount . " data berhasil diimport.";
            }
            throw new \Exception($errorMessage);
        }
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}