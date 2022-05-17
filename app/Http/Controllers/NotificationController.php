<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Message;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user=auth()->user();

        $notification=$user
            ->notifications()
            ->latest()
            ->paginate(20);

        $conversations_ids=$user
            ->conversations()
            ->pluck('id');

        $unread_messages=Message::
        whereIn('conversation_id', $conversations_ids)
            ->where([
                ['read_at','=', null],
                ['user_id', '<>', $user->id]
            ])
            ->count();
        
        return response()->json([
            'notification' => $notification,
            'unread_messages'=>$unread_messages
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Request $request)
    {
        Notification::find($request->id)->update(['read_at' => now()]);

        return response()->json(['timestamp'=> now()], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
    }

    /**
     * Remove all notifications.
     *
     * @param  \App\Models\notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(Request $request)
    {
        auth()->user()->notifications()->delete();
    }
}
