<?php
namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use App\Events\newMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class newMessageListen
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
     *
     * @return void
     */
    public function handle(newMessage $event)
    {
        return $event;
    }
}
