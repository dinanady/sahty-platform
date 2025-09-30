<?php
namespace App\Notifications;

use App\Models\Drug;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DrugStatusUpdated extends Notification
{
    use Queueable;

    public $drug;

    public function __construct(Drug $drug)
    {
        $this->drug = $drug;
    }

    public function via($notifiable)
    {
        return ['database']; // أو ['mail','database'] لو عايزة ايميل
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'تم تحديث حالة الدواء',
            'message' => "تم تحديث حالة الدواء {$this->drug->name} إلى {$this->drug->approval_status}",
            'drug_id' => $this->drug->id,
        ];
    }
}
