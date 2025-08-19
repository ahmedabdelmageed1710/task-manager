<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TaskService; // Assuming you have a TaskService for handling task logic
use App\Helpers\HttpStatusCodes; // Assuming you have a helper for HTTP status codes
use App\Http\Requests\TasksRequests\StoreTaskRequest;
use App\Http\Requests\TasksRequests\AssignTaskRequest; // Assuming you have a request class for task validation
use App\Http\Requests\TasksRequests\UpdateTaskStatusRequest; // Assuming you have a request class for updating task status
use App\Repositories\ResponseRepository; // Assuming you have a ResponseRepository for handling responses
class TaskController extends Controller
{
    protected $taskService;
    /**
     * TaskController constructor.
     *
     * @param TaskService $taskService
     * @param ResponseRepository $response
     */
    public function __construct(TaskService $taskService, ResponseRepository $response)
    {
        parent::__construct($response);
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $res = $this->taskService->createTask($request->validated());
            return $this->response->success($res['data'], $res['message'], HttpStatusCodes::HTTP_CREATED);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $res = $this->taskService->getTask($id);
            return $this->response->success($res['data'], $res['message'], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskRequest $request, $id)
    {
        try {
            // Use the TaskService to update the task
            $res = $this->taskService->updateTask($request->validated(), $id);
            return $this->response->success($res['data'], $res['message'], $res['statusCode']);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $res = $this->taskService->deleteTask($id);
            return $this->response->success($res['data'], $res['message'], $res['statusCode']);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }

    /**
     * Assign a task to a user.
     */

    public function assignTask(AssignTaskRequest $request)
    {
        try {
            // Use the TaskService to assign the task
            $res = $this->taskService->assignTask($request->validated());
            return $this->response->success($res['data'], $res['message'], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }

    /**
     * Get all tasks.
     */
    public function allTasks(Request $request)
    {
        try {
            $res = $this->taskService->allTasks($request);
            return $this->response->success($res['data'], $res['message'], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }


    /**
     * Get tasks assigned to the authenticated user.
     */
    public function allTasksByUser(Request $request)
    {
        try {
            $res = $this->taskService->allTasksByUser();
            return $this->response->success($res['data'], $res['message'], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }

    /**
     * Get only task assigned to the authenticated user.
     */

    public function getUserTask(Request $request, $id)
    {
        try {
            $res = $this->taskService->getUserTask($request, $id);
            // Return success response
            return $this->response->success($res['data'], $res['message'], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }

    /**
     * Update the status of a task for the authenticated user.
     */
    public function updateUserTaskStatus(UpdateTaskStatusRequest $request, $id)
    {
        try {
            $res = $this->taskService->updateUserTaskStatus($request->validated(), $id);
            // Return success response
            return $this->response->success($res['data'], $res['message'], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), null, $exception->getCode()); // Return error response with exception message and code
        }
    }

}
