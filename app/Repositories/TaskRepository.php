<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use App\Services\UserService;
use App\Helpers\HttpStatusCodes; // Assuming you have a helper for HTTP status codes


class TaskRepository implements TaskRepositoryInterface
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handleRequest($request)
    {
        // assign manager if not provided
        if (!isset($request['manager_id'])) { // If the manager_id is not provided, assign the authenticated user's ID as the manager.
            $request['manager_id'] = auth()->user()->id;
        }

        return $request;
    }

    /**
     * Store a new Task.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTask($request)
    {
        // Create a new task instance with the provided data.
        $request = $this->handleRequest($request);
        $task = Task::create($request);
        return [ 'data' => $task , 'message' => 'Task created successfully.'];
    }

    public function getTask($id)
    {
        // Find the task by ID.
        $task = $this->findTaskById($id);

        // If the task is not found, return an error response.
        if (!$task) {
            return ['message' => 'Task not found', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_NOT_FOUND];
        }
        // Return the task data.
        $task->load('assignee', 'manager', 'dependencies'); // Load related models if
        return ['message' => 'Get task successfully.', 'data' => $task, 'statusCode' => HttpStatusCodes::HTTP_OK];
    }

    public function findTaskById($id)
    {
        // Find the task by ID.
        return Task::find($id);
    }

    public function findTaskByTitle($title)
    {
        // Find the task by title.
        return Task::where('title', 'like', '%' . $title . '%' )->first();
    }

    public function updateTask($request, $id)
    {
        $task = $this->findTaskById($id);
        if (!$task) {
            return ['message' => 'Task not found', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_NOT_FOUND];
        }
        $task->update($request);
        return ['message' => 'Task updated successfully.', 'data' => $task, 'statusCode' => HttpStatusCodes::HTTP_OK];
    }

    public function deleteTask($id)
    {
        $task = $this->findTaskById($id);
        if (!$task) {
            return ['message' => 'Task not found', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_NOT_FOUND];
        }
        // Delete the task.
        $task->delete();
        return ['message' => 'Task deleted successfully.', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_OK];
    }


    public function assignTask($request)
    {
        // Logic for assigning a task to a user.
        $task = $this->findTaskById($request['task_id']);
        if (!$task) {
            return ['data' => ['status' => 'error', 'message' => 'Task not found'], 'statusCode' => HttpStatusCodes::HTTP_NOT_FOUND];
        }
        $user = $this->userService->findUserById($request['user_id']);
        if (!$user) {
            return ['message' => 'User not found', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_NOT_FOUND];
        }

        $task->update(['user_id' => $user->id]);

        return ['message' => 'Task assigned successfully.', 'data' => $task, 'statusCode' => HttpStatusCodes::HTTP_OK];
    }


    public function getAllTasks($request)
    {
        // Logic to retrieve all tasks, possibly with pagination or filtering.
        $tasks = Task::with('assignee', 'manager')->where(  function ($query) use ($request) {
            if ($request->has('status')) {
                $query->where('status', $request->status); // Filter tasks by status
            }
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id); // Filter tasks by user ID
            }

            if ($request->has('title')) {
                $query->where('title', 'like', '%' . $request->title . '%'); // Filter tasks by title
            }

            if ($request->has('start_date')) {
                $query->whereDate('due_date', '>=', $request->start_date); // Filter tasks by start date in range
            }

            if ($request->has('end_date')) {
                $query->whereDate('due_date', '<=', $request->end_date); // Filter tasks by end date in range
            }
        })->paginate(10);
        return ['message' => 'All tasks retrieved successfully.', 'data' => $tasks, 'statusCode' => HttpStatusCodes::HTTP_OK];
    }

    public function getAllTasksByUser()
    {
        // Logic to retrieve all tasks assigned to a authenticated user.
        $userId = auth()->id(); // Get the authenticated user's ID.
        $tasks = Task::where('user_id', $userId)->with('assignee', 'manager', 'dependencies')->paginate(10);
        return ['message' => 'All tasks for user retrieved successfully.', 'data' => $tasks, 'statusCode' => HttpStatusCodes::HTTP_OK];
    }


    public function getUserTask($request, $id)
    {
        // Logic to retrieve only one task assigned to a specific user.
        $task = $this->findTaskById($id);
        if (!$task) {
            return ['message' => 'Task not found', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_NOT_FOUND];
        }
        // Ensure the task is assigned to the user making the request.
        if ($task->user_id !== auth()->id()) {
            return ['message' => 'Unauthorized access to this task', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_FORBIDDEN];
        }
        $task->load('assignee', 'manager', 'dependencies');
        return ['message' => 'Task for user retrieved successfully.', 'data' => $task, 'statusCode' => HttpStatusCodes::HTTP_OK];
    }


    public function updateUserTaskStatus($request, $id)
    {
        $task = $this->findTaskById($id);
        if (!$task) {
            return ['message' => 'Task not found', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_NOT_FOUND];
        }

        // Ensure the task is assigned to the user making the request.
        if ($task->user_id !== auth()->id()) {
            return ['message' => 'Unauthorized access to this task', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_FORBIDDEN];
        }

        if($request['status'] === 'completed'){
            $dependencies = $task->dependencies->where('status', 'pending')->count();
            if ($dependencies > 0) {
                return ['message' => 'Cannot mark task as completed without completing its dependencies', 'data' => null, 'statusCode' => HttpStatusCodes::HTTP_BAD_REQUEST];
            }
        }

        $task->update(['status' => $request['status']]);

        return ['message' => 'Task status updated successfully.', 'data' => $task, 'statusCode' => HttpStatusCodes::HTTP_OK];
    }
}
