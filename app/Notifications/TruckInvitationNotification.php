<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TruckInvitationNotification extends Notification
{
    use Queueable;
    public $truck;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🚛 Truck Invitation')
            ->line("You have been invited to manage Truck #{$this->truck->truck_number} ({$this->truck->truck_type}).")
            ->action('View Truck', url('/trucks/' . $this->truck->id))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'truck_id' => $this->truck->id,
            'message' => "You have been invited to manage Truck #{$this->truck->truck_number}.",
        ];
    }
}
