<?php
namespace App\Providers;

use App\Models\comment;
use App\Policies\CommentsPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Book' => 'App\Policies\BookPolicy',
        'App\Models\ImproveRecipe' => 'App\Policies\ImproveRecipePolicy',
        'App\Models\Comment' => 'App\Policies\CommentsPolicy',
        'App\Models\Recipe' => 'App\Policies\RecipePolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Conversation' => 'App\Policies\ConversationPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage())
            ->greeting(__('email.welcome'))
            ->subject(__('email.verify_profile'))
            ->line(__('email.click_to_button'))
            ->action(__('email.verify_button'), $url);
        });
    }
}
