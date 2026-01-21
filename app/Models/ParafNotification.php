<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParafNotification extends Notification
{
    use Queueable;

    protected $data;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type; // 'bangun_pagi', 'beribadah', 'bermasyarakat'
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        $messages = [
            'bangun_pagi' => [
                'title' => 'Paraf Bangun Pagi Diperlukan',
                'message' => 'Anak Anda ' . ($this->data->siswa->nama_lengkap ?? '') . ' telah checklist bangun pagi pada ' . ($this->data->tanggal_formatted ?? '') . ' pukul ' . ($this->data->pukul_formatted ?? '') . '. Mohon berikan paraf.',
                'icon' => '☀️',
                'color' => 'yellow'
            ],
            'beribadah' => [
                'title' => 'Paraf Ibadah Sholat Diperlukan',
                'message' => 'Anak Anda ' . ($this->data->siswa->nama_lengkap ?? '') . ' telah mencatat ibadah sholat pada ' . ($this->data->tanggal_formatted ?? '') . '. Mohon berikan paraf.',
                'icon' => '🕌',
                'color' => 'green'
            ],
            'bermasyarakat' => [
                'title' => 'Paraf Kegiatan Sosial Diperlukan',
                'message' => 'Anak Anda ' . ($this->data->siswa->nama_lengkap ?? '') . ' telah mengikuti kegiatan "' . ($this->data->nama_kegiatan ?? '') . '" pada ' . ($this->data->tanggal_formatted ?? '') . '. Mohon berikan paraf.',
                'icon' => '🤝',
                'color' => 'blue'
            ]
        ];

        $msg = $messages[$this->type] ?? $messages['bangun_pagi'];

        return [
            'type' => $this->type,
            'title' => $msg['title'],
            'message' => $msg['message'],
            'icon' => $msg['icon'],
            'color' => $msg['color'],
            'data_id' => $this->data->id,
            'siswa_id' => $this->data->siswa_id ?? null,
            'siswa_nama' => $this->data->siswa->nama_lengkap ?? '',
            'url' => $this->getUrl(),
            'created_at' => now()->toDateTimeString()
        ];
    }

    private function getUrl()
    {
        $routes = [
            'bangun_pagi' => route('paraf-ortu-bangun-pagi.index'),
            'beribadah' => route('paraf-ortu-beribadah.index'),
            'bermasyarakat' => route('paraf-ortu-bermasyarakat.index')
        ];

        return $routes[$this->type] ?? route('orangtua.dashboard');
    }
}