<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BangunPagi;
use App\Models\TidurCepat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SyncBangunPagiTidurCepat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:bangun-pagi-tidur-cepat 
                            {--tanggal-mulai= : Tanggal mulai (Y-m-d)} 
                            {--tanggal-selesai= : Tanggal selesai (Y-m-d)}
                            {--force : Force sync semua data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data Bangun Pagi ke Tidur Cepat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== MULAI SINKRONISASI ===');
        
        $tanggalMulai = $this->option('tanggal-mulai') ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $tanggalSelesai = $this->option('tanggal-selesai') ?? Carbon::now()->format('Y-m-d');
        $force = $this->option('force');
        
        $this->info("Tanggal Mulai: {$tanggalMulai}");
        $this->info("Tanggal Selesai: {$tanggalSelesai}");
        $this->info("Force Mode: " . ($force ? 'YA' : 'TIDAK'));
        $this->newLine();
        
        try {
            DB::beginTransaction();
            
            // Ambil semua BangunPagi dalam rentang tanggal
            $bangunPagiList = BangunPagi::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                ->orderBy('tanggal')
                ->get();
            
            $this->info("Total BangunPagi ditemukan: " . $bangunPagiList->count());
            $this->newLine();
            
            $bar = $this->output->createProgressBar($bangunPagiList->count());
            $bar->start();
            
            $successCount = 0;
            $failedCount = 0;
            $skippedCount = 0;
            
            foreach ($bangunPagiList as $bp) {
                // Cari TidurCepat di hari sebelumnya
                $tanggalTidur = Carbon::parse($bp->tanggal)->subDay()->format('Y-m-d');
                
                $tidurCepat = TidurCepat::where('siswa_id', $bp->siswa_id)
                    ->whereDate('tanggal', $tanggalTidur)
                    ->first();
                
                if (!$tidurCepat) {
                    $skippedCount++;
                    $bar->advance();
                    continue;
                }
                
                // Jika sudah tersinkron dan bukan force mode, skip
                if (!$force && $tidurCepat->bangun_pagi_id == $bp->id && $tidurCepat->pukul_bangun == $bp->pukul) {
                    $skippedCount++;
                    $bar->advance();
                    continue;
                }
                
                // Update TidurCepat
                $updated = $tidurCepat->update([
                    'pukul_bangun' => $bp->pukul,
                    'bangun_pagi_id' => $bp->id,
                ]);
                
                if ($updated) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
                
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine(2);
            
            DB::commit();
            
            // Summary
            $this->info('=== HASIL SINKRONISASI ===');
            $this->table(
                ['Status', 'Jumlah'],
                [
                    ['Berhasil', $successCount],
                    ['Gagal', $failedCount],
                    ['Dilewati', $skippedCount],
                    ['Total', $bangunPagiList->count()],
                ]
            );
            
            $this->newLine();
            $this->info('✓ Sinkronisasi selesai!');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->error('Error: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            
            return Command::FAILURE;
        }
    }
}