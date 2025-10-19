<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailChangeApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $approved;
    protected $newEmail;

    /**
     * Create a new notification instance.
     */
    public function __construct(bool $approved, string $newEmail)
    {
        $this->approved = $approved;
        $this->newEmail = $newEmail;
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
        if ($this->approved) {
            return (new MailMessage)
                ->subject('Email Change Approved')
                ->line('Your email change request has been approved.')
                ->line('New Email: ' . $this->newEmail)
                ->line('You may need to verify your new email address.');
        } else {
            return (new MailMessage)
                ->subject('Email Change Rejected')
                ->line('Your email change request has been rejected.')
                ->line('Requested Email: ' . $this->newEmail)
                ->line('Please contact an administrator for more information.');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->approved) {
            return [
                'type' => 'email_change_approved',
                'message' => 'Your email change request to ' . $this->newEmail . ' has been approved.',
                'new_email' => $this->newEmail,
            ];
        } else {
            return [
                'type' => 'email_change_rejected',
                'message' => 'Your email change request to ' . $this->newEmail . ' has been rejected.',
                'requested_email' => $this->newEmail,
            ];
        }
    }
}
