<?php
namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $todos= Todo::all()->load(['tasks' => function ($q) {
                $q->orderBy('order', 'asc');
            }]);
            return $todos;
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\todos  $todos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $todo->update([
            'card_name'=> $request->card_name
        ]);
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
            'card_name' => 'required',
            
        ]);

        $todo=Todo::create([
            'card_name'=> $request->card_name,
        ]);
        return $todo->load('tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\todos  $todos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
    }
}
