<?php
namespace App\Http\Controllers;

use App\Events\newConversation;
use App\Events\conversationDeletedByUser;
use App\Models\Conversation;
use App\Notifications\notifyUser;
use App\Models\User;
use App\Events\newMessage;
use App\Models\Message;
use App\Traits\FileHandler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConversationsController extends Controller
{
    use FileHandler;
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();

            $conversations = $user->conversations()
                ->wherePivotNull('deleted_at')
                ->with('users')
                ->withCount(['messages' => function (Builder $query) use ($user) {
                    $query->where([['read_at', '=', null], ['user_id', '<>', $user->id], ]);
                }])
                ->latest()
                ->simplePaginate(16);

            return response($conversations, 200);
        }
        return view('conversations');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required| String',
            'text'    => 'required| String',
            'touser' => 'required| integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user=auth()->user();

        $reciever = User::find($request->touser);

        $conversation = new Conversation();
        $conversation->subject = $request->subject;
        $conversation->save();

        $reciever->conversations()->attach($conversation->id);

        $user->conversations()->attach($conversation->id);

        $newmessage = $user->messages()->create([
            'conversation_id' => $conversation->id,
            'text' => $request->text,
            'images'=> json_encode($images_path ?? null),
        ]);

        if ($request->images) {
            foreach ($request->images as $image) {
                $storage_path=storage_path('app/private/images/conversations/'.$conversation->id);
                $thumb = $this->uploadImage($image,$storage_path,150,150, true);
                $image = $this->uploadImage($image,$storage_path,800,800, false);
                $newmessage->images()->create(
                    [
                        'url'=> 'images/conversations/'.$request->conversation_id.'/'.$image,
                        'thumbnail_url'=> 'images/conversations/'.$request->conversation_id.'/'.$thumb,
                    ]
                );
            }
        };

        $message = __('general.user_send_message_for_you');
        $url = url('/spravy');
        $data = [
            'message' => $message,
            'icon'=> 'avatar',
            'user' => $user,
            'link' =>   $url
        ];

        $reciever->notify(new notifyUser($data));

        broadcast(new newMessage($newmessage->load('user')));
        broadcast(new newConversation($reciever->id,$conversation->load('users') ));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $messages = Message::where('conversation_id', $conversation->id)
            ->with('user','images')
            ->simplePaginate(50);

        $user=auth()->user();
        
        Message::where(
                [
                    ['conversation_id', '=', $conversation->id],
                    ['user_id', '<>', $user->id], 
                    ['read_at', '=', null]
                ]
            )
            ->update(
                [
                    'read_at' => now()
                ]
            );
        
        return response($messages, 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conversation $conversation)
    {
        $this->authorize('delete', $conversation);
        $user=auth()->user();
        $delete_conversation=true;

        $user->conversations()
            ->updateExistingPivot($conversation->id, ['deleted_at' => date("Y-m-d H:i:s"),]);
       
        foreach ($conversation->users as $user) {
            
           if(!$user->pivot->deleted_at){
                broadcast(new conversationDeletedByUser($user, $conversation));
               return $delete_conversation = false ;
           }
        }

        if ($delete_conversation) {
            Storage::deleteDirectory("private/images/conversations/$conversation->id/");
            return $conversation->delete();
        }

    }
}
