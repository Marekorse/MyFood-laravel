<?php
namespace App\Listeners;

use App\Events\newConversation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class newConversationListen
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  newConversation  $event
     * @return void
     */
    public function handle(newConversation $event)
    {
        return $event;
    }
}
