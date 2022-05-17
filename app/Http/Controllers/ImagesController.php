<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\book;

class ImagesController extends Controller
{
    /**
     * Protecting conversations images.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function showConversationImage(Request $request, $id, $filename, $thumb =false)
    {
        $conversation=Conversation::find($id);
        $this->authorize('view', $conversation);
        if ($thumb) {
            $path = 'app/private/images/conversations/'.$id.'/'.$filename.'-thumb';
        }
        else $path = 'app/private/images/conversations/'.$id.'/'.$filename;
        
        return response()->download(storage_path($path), null, [], null);

    }
        /**
     * Protecting recipesBook images.       
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function showRecipeBookImage(Request $request, $book_id, $filename)
    {
        $book=Book::find($book_id);
        $this->authorize('viewAny', $book);

        $path = 'app/private/images/ImprovedRecipes/'.$book_id.'/'.$filename;

        return response()->download(storage_path($path), null, [], null);


    }

}
