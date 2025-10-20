<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewQuotationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $quotation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
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
        return (new MailMessage)
            ->subject('Penawaran Baru dari Sales')
            ->line('Ada penawaran baru yang perlu diproses.')
            ->line('Customer: ' . $this->quotation->nama_customer)
            ->line('Sales: ' . $this->quotation->sales_person)
            ->action('Lihat Penawaran', route('sap.daftar-penawaran'))
            ->line('Silakan input nomor SAP untuk penawaran ini.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_quotation',
            'message' => 'Penawaran baru dari ' . $this->quotation->sales_person . ' untuk customer ' . $this->quotation->nama_customer,
            'action_url' => route('sap.daftar-penawaran'),
            'quotation_id' => $this->quotation->id,
        ];
    }
}
