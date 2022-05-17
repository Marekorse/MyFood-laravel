<?php
namespace App\Notifications;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Symfony\Component\VarDumper\Cloner\Data;

class notifyUser extends Notification implements ShouldBroadcast
{
    use Queueable;
    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {

        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if(is_array($this->data)){
           return ['database', 'broadcast']; 
        }
        else return['broadcast'];

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return
            $this->data;
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {   
        return new BroadcastMessage(
            ['data'=> $this->data]
        );
    }
}
