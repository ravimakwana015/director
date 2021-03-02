<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class UserNeedsConfirmation.
 */
class MessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var
     */
    protected $message;
    protected $sender;

    /**
     * UserNeedsConfirmation constructor.
     *
     * @param $message
     */
    public function __construct($message,$sender)
    {

        $this->message = $message;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
        ];
    }

    public function toDatabase($notifiable)
    {

        return [
            'message' => $this->message,
            'name' => $this->sender->first_name,
            'sender_id' => $this->sender->id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $sender_name=$this->sender->first_name.' '.$this->sender->last_name;
        return (new MailMessage())
        ->subject(config('app.name').': '.'You Have New Message From'.' '.$sender_name)
        ->line('Message is. '.$this->message)
        ->action('Notification Action', route('chat.show', $this->sender->id))
        ->line('Thank you for using our application!');
    }
}
