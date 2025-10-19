<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistrationApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $approved;
    protected $role;

    /**
     * Create a new notification instance.
     */
    public function __construct(bool $approved, string $role)
    {
        $this->approved = $approved;
        $this->role = $role;
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
                ->subject('Registration Approved')
                ->line('Congratulations! Your registration has been approved.')
                ->line('Role Assigned: ' . ucfirst($this->role))
                ->line('You can now log in and access the system.')
                ->action('Login Now', url('/login'));
        } else {
            return (new MailMessage)
                ->subject('Registration Rejected')
                ->line('We regret to inform you that your registration has been rejected.')
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
                'type' => 'registration_approved',
                'message' => 'Your registration has been approved. You are now assigned as ' . ucfirst($this->role) . '.',
                'role' => $this->role,
            ];
        } else {
            return [
                'type' => 'registration_rejected',
                'message' => 'Your registration has been rejected.',
            ];
        }
    }
}
