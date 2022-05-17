<?php
namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use App\Models\Conversation;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        Broadcast::channel('conversation.{conversation_id}', function ($user, $convid) {
            return  auth()->user()->conversations()->where('id', $convid)->exists();
        });
        Broadcast::channel('App.Models.User.{id}', function ($user) {
            return $user->id === auth()->id();
        });
        Broadcast::channel('conversations.{user_id}', function ($user) {
            return $user->id === auth()->id();
        });
        require base_path('routes/channels.php');

        Broadcast::channel('online', function ($user) {
            return auth()->check();
        });
    }
}
