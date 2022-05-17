<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\FileHandler;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    use FileHandler;
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'avatar' => ' image ',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $book=$user->book()
            ->create(
                [
                    'name'=>__('general.recipes_book_default').' '.$user->name,
                    'slug' => Str::slug(__('general.recipes_book_default').' '.$user->name),
                ]
            );
        $book->privacy()->sync([3]); 

        if ($request->avatar) {

            $storage_path=storage_path('app/public/images/users/'.$user->id);
            $image=$this->uploadImage($request->avatar,$storage_path,800,800, false);
            $thumb=$this->uploadImage($request->avatar,$storage_path,200,200, true);
            $this->createAvatar($storage_path.'/'.$thumb,400);
            $user->images()
                ->create(
                    [
                        'url'=>'images/users/' .$user->id .'/'. $image,
                        'thumbnail_url'=> 'images/users/'.$user->id.'/'.$thumb,
                        'name'=>'avatar'
                    ]
                );
        }

        Auth::login($user);

        event(new Registered($user));
        
        return redirect(RouteServiceProvider::HOME);
    }
}
