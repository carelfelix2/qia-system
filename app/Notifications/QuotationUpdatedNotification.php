<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotationUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quotation;
    protected $changes;
    protected $updaterRole;

    /**
     * Create a new notification instance.
     */
    public function __construct(Quotation $quotation, array $changes = [], string $updaterRole = 'sales')
    {
        $this->quotation = $quotation;
        $this->changes = $changes;
        $this->updaterRole = $updaterRole;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $changesText = !empty($this->changes) ? 'Perubahan: ' . implode(', ', $this->changes) : 'Ada perubahan pada penawaran';

        $updaterText = $this->updaterRole === 'sap' ? 'Inputer SAP' : 'Sales';

        return (new MailMessage)
            ->subject('Penawaran Diperbarui')
            ->line('Penawaran telah diperbarui oleh ' . $updaterText . '.')
            ->line('Customer: ' . $this->quotation->nama_customer)
            ->line('Sales: ' . $this->quotation->sales_person)
            ->line($changesText)
            ->action('Lihat Penawaran', $this->updaterRole === 'sap' ? route('sales.daftar-penawaran') : route('sap.daftar-penawaran'))
            ->line('Silakan periksa perubahan pada penawaran ini.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $updaterText = $this->updaterRole === 'sap' ? 'Inputer SAP' : 'Sales';

        // Create detailed message with changes
        $changesText = !empty($this->changes) ? ' (' . implode(', ', $this->changes) . ')' : '';

        return [
            'type' => 'quotation_updated',
            'message' => 'Penawaran diperbarui untuk customer ' . $this->quotation->nama_customer . ' oleh ' . $updaterText . $changesText,
            'action_url' => route('sap.log-perubahan'),
            'quotation_id' => $this->quotation->id,
            'changes' => $this->changes,
        ];
    }
}
