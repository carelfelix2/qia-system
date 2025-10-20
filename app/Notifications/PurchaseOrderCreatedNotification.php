<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseOrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $purchaseOrder;
    protected $userRole;

    /**
     * Create a new notification instance.
     */
    public function __construct(PurchaseOrder $purchaseOrder, string $userRole)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->userRole = $userRole;
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
        $quotation = $this->purchaseOrder->quotation;

        return (new MailMessage)
            ->subject('Purchase Order Baru Dibuat')
            ->line('Purchase Order baru telah dibuat dari penawaran Anda.')
            ->line('Customer: ' . $quotation->nama_customer)
            ->line('No PO: ' . $this->purchaseOrder->po_number)
            ->line('Total Amount: Rp ' . number_format($this->purchaseOrder->total_amount, 0, ',', '.'))
            ->action('Lihat Purchase Order', route('purchase-orders.show', $this->purchaseOrder))
            ->line('Silakan periksa detail Purchase Order untuk informasi lebih lanjut.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $quotation = $this->purchaseOrder->quotation;

        return [
            'type' => 'purchase_order_created',
            'message' => 'Purchase Order baru dibuat untuk customer ' . $quotation->nama_customer . ' (PO: ' . $this->purchaseOrder->po_number . ')',
            'action_url' => route('purchase-orders.show', $this->purchaseOrder),
            'purchase_order_id' => $this->purchaseOrder->id,
            'quotation_id' => $quotation->id,
            'user_role' => $this->userRole,
        ];
    }
}
