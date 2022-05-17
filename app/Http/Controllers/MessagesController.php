<?php
namespace App\Http\Controllers;
use App\Notifications\notifyUser;
use App\Models\Message;
use App\Events\newMessage;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\FileHandler;

class MessagesController extends Controller
{
    use FileHandler;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'text'    => 'String|nullable',
            'conversation_id' => 'required| integer',
            'touser'=>'required| integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $new_message= auth()
            ->user()
            ->messages()
            ->create(
                [
                    'conversation_id'=>$request->conversation_id,
                    'text'=> $request->text,
                    'images'=> json_encode($images_path ?? null),

                ]
            );

        if ($request->images) {
            foreach ($request->images as $image) {
                $storage_path=storage_path('app/private/images/conversations/'.$request->conversation_id);
                $thumb = $this->uploadImage($image,$storage_path,150,150, true);
                $image = $this->uploadImage($image,$storage_path,800,800, false);
                $new_message->images()->create(
                    [
                        'url'=> 'images/conversations/'.$request->conversation_id.'/'.$image,
                        'thumbnail_url'=> 'images/conversations/'.$request->conversation_id.'/'.$thumb,
                    ]
                );
            }
        };

        $touser=User::find($request->touser);
        $notification='unreadMessage';      
        $touser->notify(new notifyUser($notification));
                
        broadcast(new newMessage($new_message->load('user','images')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        if ($message->images) {
            foreach (json_decode($message->images) as $image) {
                $this->deleteImage($image->url, 'private', $image->thumbnail_url);
            }
        }

        $message->delete();
    }
}

