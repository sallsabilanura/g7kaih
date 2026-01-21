<?php

namespace App\Http\Controllers;

use App\Models\PengaturanWaktuMakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PengaturanWaktuMakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengaturanWaktu = PengaturanWaktuMakan::orderByRaw(
            "FIELD(jenis_makanan, 'sarapan', 'makan_siang', 'makan_malam')"
        )->get();
        
        $jenisLabels = [
            'sarapan' => 'Sarapan',
            'makan_siang' => 'Makan Siang', 
            'makan_malam' => 'Makan Malam'
        ];

        return view('pengaturan-waktu-makan.index', compact('pengaturanWaktu', 'jenisLabels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisLabels = [
            'sarapan' => 'Sarapan',
            'makan_siang' => 'Makan Siang', 
            'makan_malam' => 'Makan Malam'
        ];

        return view('pengaturan-waktu-makan.create', compact('jenisLabels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validatePengaturan($request);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $existing = PengaturanWaktuMakan::where('jenis_makanan', $request->jenis_makanan)->first();
            
            if ($existing) {
                return back()->with('error', 'Pengaturan untuk ' . $this->getJenisLabel($request->jenis_makanan) . ' sudah ada. Silakan edit pengaturan yang sudah ada.')
                            ->withInput();
            }

            $data = $this->prepareData($request);
            $data['jenis_makanan'] = $request->jenis_makanan;

            PengaturanWaktuMakan::create($data);

            return redirect()->route('pengaturan-waktu-makan.index')
                           ->with('success', 'Pengaturan waktu ' . $this->getJenisLabel($request->jenis_makanan) . ' berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Error create pengaturan waktu:', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jenis)
    {
        $pengaturanWaktu = PengaturanWaktuMakan::where('jenis_makanan', $jenis)->first();
        
        if (!$pengaturanWaktu) {
            return redirect()->route('pengaturan-waktu-makan.index')
                           ->with('error', 'Pengaturan waktu tidak ditemukan.');
        }

        $jenisLabels = [
            'sarapan' => 'Sarapan',
            'makan_siang' => 'Makan Siang', 
            'makan_malam' => 'Makan Malam'
        ];

        $jenisLabel = $jenisLabels[$jenis] ?? ucfirst(str_replace('_', ' ', $jenis));

        return view('pengaturan-waktu-makan.edit', compact('pengaturanWaktu', 'jenis', 'jenisLabel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $jenis)
    {
        $pengaturanWaktu = PengaturanWaktuMakan::where('jenis_makanan', $jenis)->first();
        
        if (!$pengaturanWaktu) {
            return back()->with('error', 'Pengaturan waktu tidak ditemukan.');
        }

        $validator = $this->validatePengaturan($request, $jenis);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $this->prepareData($request);

            $pengaturanWaktu->update($data);

            return redirect()->route('pengaturan-waktu-makan.index')
                           ->with('success', 'Pengaturan waktu ' . $this->getJenisLabel($jenis) . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error update pengaturan waktu:', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jenis)
    {
        $pengaturanWaktu = PengaturanWaktuMakan::where('jenis_makanan', $jenis)->first();
        
        if (!$pengaturanWaktu) {
            return redirect()->route('pengaturan-waktu-makan.index')
                           ->with('error', 'Pengaturan waktu tidak ditemukan.');
        }

        try {
            $pengaturanWaktu->delete();

            return redirect()->route('pengaturan-waktu-makan.index')
                           ->with('success', 'Pengaturan waktu berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error delete pengaturan waktu:', [
                'error' => $e->getMessage(),
                'jenis' => $jenis
            ]);

            return redirect()->route('pengaturan-waktu-makan.index')
                           ->with('error', 'Gagal menghapus pengaturan waktu: ' . $e->getMessage());
        }
    }

    /**
     * Reset to default values
     */
    public function reset($jenis)
    {
        $pengaturanWaktu = PengaturanWaktuMakan::where('jenis_makanan', $jenis)->first();
        
        if (!$pengaturanWaktu) {
            return redirect()->route('pengaturan-waktu-makan.index')
                           ->with('error', 'Pengaturan waktu tidak ditemukan.');
        }

        $defaults = $this->getDefaultPengaturan($jenis);
        unset($defaults['jenis_makanan']);

        try {
            $pengaturanWaktu->update($defaults);

            return redirect()->route('pengaturan-waktu-makan.index')
                           ->with('success', 'Pengaturan waktu berhasil direset ke default!');
        } catch (\Exception $e) {
            Log::error('Error reset pengaturan waktu:', [
                'error' => $e->getMessage(),
                'jenis' => $jenis
            ]);

            return redirect()->route('pengaturan-waktu-makan.index')
                           ->with('error', 'Gagal mereset pengaturan waktu: ' . $e->getMessage());
        }
    }

    /**
     * Validate pengaturan data
     */
    private function validatePengaturan(Request $request, $jenis = null)
    {
        $rules = [
            'jenis_makanan' => 'required|in:sarapan,makan_siang,makan_malam',
            'waktu_100_start' => 'required|date_format:H:i',
            'waktu_100_end' => 'required|date_format:H:i',
            'nilai_100' => 'required|integer|min:0|max:1000',
            'waktu_70_start' => 'required|date_format:H:i',
            'waktu_70_end' => 'required|date_format:H:i',
            'nilai_70' => 'required|integer|min:0|max:1000',
            'nilai_terlambat' => 'required|integer|min:0|max:1000',
        ];

        if ($jenis) {
            unset($rules['jenis_makanan']);
        }

        $validator = Validator::make($request->all(), $rules, [
            'jenis_makanan.required' => 'Jenis makanan harus dipilih',
            'jenis_makanan.in' => 'Jenis makanan yang dipilih tidak valid',
            'waktu_100_start.required' => 'Waktu mulai kategori 1 harus diisi',
            'waktu_100_end.required' => 'Waktu selesai kategori 1 harus diisi',
            'waktu_70_start.required' => 'Waktu mulai kategori 2 harus diisi',
            'waktu_70_end.required' => 'Waktu selesai kategori 2 harus diisi',
            'nilai_100.required' => 'Nilai kategori 1 harus diisi',
            'nilai_70.required' => 'Nilai kategori 2 harus diisi',
            'nilai_terlambat.required' => 'Nilai terlambat harus diisi',
        ]);

        $validator->after(function ($validator) use ($request, $jenis) {
            $waktu100Start = $request->waktu_100_start;
            $waktu100End = $request->waktu_100_end;
            $waktu70Start = $request->waktu_70_start;
            $waktu70End = $request->waktu_70_end;

            if ($waktu100Start && $waktu100End && $waktu100Start >= $waktu100End) {
                $validator->errors()->add(
                    'waktu_100_end', 
                    'Waktu selesai Kategori 1 harus setelah waktu mulai Kategori 1'
                );
            }

            if ($waktu70Start && $waktu70End && $waktu70Start >= $waktu70End) {
                $validator->errors()->add(
                    'waktu_70_end', 
                    'Waktu selesai Kategori 2 harus setelah waktu mulai Kategori 2'
                );
            }

            if (!$jenis && $request->jenis_makanan) {
                $existing = PengaturanWaktuMakan::where('jenis_makanan', $request->jenis_makanan)->first();
                if ($existing) {
                    $validator->errors()->add(
                        'jenis_makanan', 
                        'Pengaturan untuk ' . $this->getJenisLabel($request->jenis_makanan) . ' sudah ada.'
                    );
                }
            }
        });

        return $validator;
    }

    /**
     * Prepare data for storage
     */
    private function prepareData(Request $request)
    {
        return [
            'waktu_100_start' => $request->waktu_100_start . ':00',
            'waktu_100_end' => $request->waktu_100_end . ':00',
            'nilai_100' => $request->nilai_100,
            'waktu_70_start' => $request->waktu_70_start . ':00',
            'waktu_70_end' => $request->waktu_70_end . ':00',
            'nilai_70' => $request->nilai_70,
            'nilai_terlambat' => $request->nilai_terlambat,
            'is_active' => $request->has('is_active') ? 1 : 0
        ];
    }

    /**
     * Get default pengaturan berdasarkan jenis
     */
    private function getDefaultPengaturan($jenis)
    {
        $defaults = [
            'sarapan' => [
                'jenis_makanan' => 'sarapan',
                'waktu_100_start' => '06:00:00',
                'waktu_100_end' => '08:00:00',
                'nilai_100' => 100,
                'waktu_70_start' => '08:00:00',
                'waktu_70_end' => '10:00:00',
                'nilai_70' => 70,
                'nilai_terlambat' => 50,
                'is_active' => true
            ],
            'makan_siang' => [
                'jenis_makanan' => 'makan_siang',
                'waktu_100_start' => '12:00:00',
                'waktu_100_end' => '13:00:00',
                'nilai_100' => 100,
                'waktu_70_start' => '13:00:00',
                'waktu_70_end' => '15:00:00',
                'nilai_70' => 70,
                'nilai_terlambat' => 50,
                'is_active' => true
            ],
            'makan_malam' => [
                'jenis_makanan' => 'makan_malam',
                'waktu_100_start' => '18:00:00',
                'waktu_100_end' => '19:00:00',
                'nilai_100' => 100,
                'waktu_70_start' => '19:00:00',
                'waktu_70_end' => '21:00:00',
                'nilai_70' => 70,
                'nilai_terlambat' => 50,
                'is_active' => true
            ],
        ];

        return $defaults[$jenis] ?? $defaults['sarapan'];
    }

    /**
     * Get label untuk jenis makanan
     */
    private function getJenisLabel($jenis)
    {
        $labels = [
            'sarapan' => 'Sarapan',
            'makan_siang' => 'Makan Siang', 
            'makan_malam' => 'Makan Malam'
        ];
        
        return $labels[$jenis] ?? ucfirst(str_replace('_', ' ', $jenis));
    }
}