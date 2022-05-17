<?php
namespace App\Http\Controllers\Auth;

use App\Notifications\notifyUser;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $message = 'Vaša mailová adresa bola overená';

        $data=[
            'message'=> $message,
            'icon'=>'info'
        ];
        $touser=$request->user();

        $touser->notify(new notifyUser($data));

        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
