<?php

namespace App\Observers;

use App\Models\BangunPagi;
use App\Models\TidurCepat;
use Carbon\Carbon;

class BangunPagiObserver
{
    /**
     * Handle the BangunPagi "created" event.
     */
    public function created(BangunPagi $bangunPagi): void
    {
        // Auto-sync to tidur cepat
        $this->syncToTidurCepat($bangunPagi);
    }

    /**
     * Handle the BangunPagi "updated" event.
     */
    public function updated(BangunPagi $bangunPagi): void
    {
        // Sync if pukul changed
        if ($bangunPagi->wasChanged('pukul')) {
            $this->syncToTidurCepat($bangunPagi);
        }
    }

    /**
     * Sync bangun pagi to tidur cepat
     */
    private function syncToTidurCepat(BangunPagi $bangunPagi): void
    {
        $tanggalTidur = Carbon::parse($bangunPagi->tanggal)->subDay();
        
        $tidurCepat = TidurCepat::where('siswa_id', $bangunPagi->siswa_id)
            ->whereDate('tanggal', $tanggalTidur)
            ->first();

        if ($tidurCepat) {
            $tidurCepat->update([
                'pukul_bangun' => $bangunPagi->pukul,
                'bangun_pagi_id' => $bangunPagi->id,
            ]);
            
            // Update durasi tidur
            if ($tidurCepat->pukul_tidur) {
                $tidurCepat->updateDurasiTidur();
            }
        }
    }
}