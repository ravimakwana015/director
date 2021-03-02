<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FriendRequestNotification extends Notification  implements ShouldQueue
{
    use Queueable;
    protected $message;
    protected $user;
    protected $subject;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message,$subject,$user)
    {

        $this->message = $message;
        $this->user = $user;
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject($this->subject)
        ->line(strip_tags($this->message))
        ->action('Notification Action', route('profile-details',str_replace(' ', '-', strtolower($this->user->username))))
        ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'data' => $this->message,
        ];
    }
}
