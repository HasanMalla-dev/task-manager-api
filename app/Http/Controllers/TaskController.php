<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TaskFilterRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index(TaskFilterRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $query = $user->tasks();
        $query->when(isset($data['status']), function ($query) use ($data) {
            $query->where('status', $data['status']);
        });
        $query->when(isset($data['title']), function ($query) use ($data) {
            $query->where('title', 'like', '%' . $data['title'] . '%');
        });
        $query->when(isset($data['sort']), function ($query) use ($data) {
            if ($data['sort'] === 'latest') {
                $query->orderby('created_at', 'desc');
            } else {
                $query->orderBy('created_at', 'asc');
            }
        });
        $tasks = $query->paginate($data['per_page'] ?? 2);
        return TaskResource::collection($tasks);
    }
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $task = $request->user()->tasks()->create($data);
        return (new TaskResource($task))->response()
            ->setStatusCode(201);
    }
    public function show(Task $task)
    {
        Gate::authorize('view', $task);
        return new TaskResource($task);
    }
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $data = $request->validated();

        if (empty($data)) {
            return response()->json([
                'message' => 'No data to update',
            ], 422);
        }
        $current_status = $task->status;
        if (isset($data['status'])) {
            $new_status = $data['status'];
            $allowedTransitions = [
                'pending' => 'in_progress',
                'in_progress' => 'completed',
            ];
            if (
                !isset($allowedTransitions[$current_status])||
                $allowedTransitions[$current_status] !== $new_status
            ) {
                return response()->json([
                    'message' => 'Invalid status transition',
                ], 422);
            }
        }
        $task->update($data);
        return new TaskResource($task);
    }
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);
        $task->delete();
        return response()->noContent();
    }
}
