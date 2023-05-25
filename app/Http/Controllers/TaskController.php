<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::with('projects')->orderBy('order_by')->get();
        return response()->json([
            'status' => true,
            'data' => $tasks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create($request->all());
        return response()->json([
            'status' => true,
            'data' => $task,
            "message" => "Task Created Successfully."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id)
    {
        $task = Task::find($id);
        $task->update([
            'task_name' => $request->task_name,
            'project_id' => $request->project_id,
            'priority' => $request->priority,
        ]);
        return response()->json([
            'data' => $task,
            'status' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::find($id)->delete();

        return response()->json([
            'status' => true
        ]);
    }


    /**
     * update the sorting Order
     *
     * @param  mixed $request
     * @return \Illuminate\Http\Response
     */
    public function updateReOrder(Request $request)
    {
        $projects = $request->input('projects');
        $order = 1;
        foreach ($projects as $project) {
            foreach ($project['tasks'] as $task) {
                Task::where('id', $task['id'])->update([
                    'project_id' => $project['id'],
                    'order_by' => $order
                ]);
                $order++;
            }
        }
        return response()->json(['success' => true]);
    }
}
