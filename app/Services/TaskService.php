<?php

namespace App\Services;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskService {

    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function createTask($request)
    {
        return $this->taskRepository->storeTask($request);
    }

    public function getTask($id)
    {
        return $this->taskRepository->getTask($id);
    }

    public function updateTask($request, $id)
    {
        return $this->taskRepository->updateTask($request, $id);
    }

    public function deleteTask($id)
    {
        return $this->taskRepository->deleteTask($id);
    }

    public function assignTask($request)
    {
        return $this->taskRepository->assignTask($request);
    }

    public function allTasks($request)
    {
        return $this->taskRepository->getAllTasks($request);
    }

    public function allTasksByUser()
    {
        return $this->taskRepository->getAllTasksByUser();
    }

    public function getUserTask($request, $id)
    {
        return $this->taskRepository->getUserTask($request, $id);
    }

    public function updateUserTaskStatus($request, $id)
    {
        return $this->taskRepository->updateUserTaskStatus($request, $id);
    }

}
