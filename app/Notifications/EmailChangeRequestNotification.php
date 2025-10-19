<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class EmailChangeRequestNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $newEmail;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $newEmail)
    {
        $this->user = $user;
        $this->newEmail = $newEmail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Change Request Pending Approval')
            ->line('A user has requested to change their email address.')
            ->line('User: ' . $this->user->name . ' (' . $this->user->email . ')')
            ->line('Requested Email: ' . $this->newEmail)
            ->action('Review Request', url('/admin/users'))
            ->line('Please review and approve or reject the email change request.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'current_email' => $this->user->email,
            'requested_email' => $this->newEmail,
            'type' => 'email_change_request',
            'message' => 'User ' . $this->user->name . ' requested email change to ' . $this->newEmail . '.',
            'action_url' => route('admin.users.index', ['user' => $this->user->id]),
        ];
    }
}
