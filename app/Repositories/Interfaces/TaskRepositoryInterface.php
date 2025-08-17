<?php
namespace App\Repositories\Interfaces;

Interface TaskRepositoryInterface{

    public function storeTask($request); // Store a new task
    public function getTask($id); // Get a specific task
    public function findTaskByTitle($title); // Find a task by its title
    public function updateTask($request , $id); // Update a specific task
    public function deleteTask($id); // Delete a specific task
    public function assignTask($request); // Assign a task to a user
    public function getAllTasks($request); // Get all tasks
    public function getAllTasksByUser(); // Get all tasks for the authenticated user
    public function getUserTask($request, $id); // Get a specific task for the authenticated user
    public function updateUserTaskStatus($request, $id); // Update the status of a task for the authenticated user
}
