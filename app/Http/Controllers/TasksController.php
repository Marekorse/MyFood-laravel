<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required',
            'order' => 'integer',
            'todo_id' =>'integer'

        ]);

        $task=Task::create([
            'task_name'=> $request->task_name,
            'todo_id' => $request->todo_id,
            'order' => $request->order,

        ]);
        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        if ($request->todo_id) {
            $task->update([
                'todo_id' => $request->todo_id,
            ]);
        };

        if ($request->task_name) {
            $task->update([
                'task_name' => $request->task_name,
            ]);
        };
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\task  $task
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request)
    {
        $tasks = Task::where('todo_id', $request->todo_id)->get();

        foreach ($tasks as $task) {
            $task->timestamps = false;
            $id = $task->id;
            foreach ($request->tasks as $new_task) {
                if ($new_task['id'] == $id) {
                    $task->update(['order' => $new_task['order']]);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
    }
}
