<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->only('current_password', 'new_password', 'new_password_confirmation');

        $request->validate(
            [
                'current_password' => 'required|current_password',
                'new_password_confirmation' => 'required',
                'new_password' => 'required|string|min:8|confirmed|different:current_password',
            ]
        );

        $user = Auth::user();

        $user->password = Hash::make($request->new_password);
        
        $user->save();

        return response()->json('{"success":"password has been successfully changed"}', 200);
    }
}
