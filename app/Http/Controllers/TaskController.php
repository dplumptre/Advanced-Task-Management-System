<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{

    private TaskService $taskService;

    public function __construct(TaskService $taskService){
        $this->taskService = $taskService;
    }

    public function index()
    {
        $tasks = Task::all();
        return ApiResponse::success($tasks);
    }


    /**
     * @throws \Exception
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());
        return ApiResponse::success($task, "Task created successfully",  Response::HTTP_CREATED);
    }

    /**
     * @throws \Exception
     */
    public function update(TaskRequest $request, $id): JsonResponse
    {
        try{
            $task =  $this->taskService->updateTask($id, $request->validated());
             return ApiResponse::success($task, "Task updated successfully");
        } catch (\Exception $e) {
            return ApiResponse::failure("Task update failed");
        }
    }

    public function show($id): JsonResponse
    {
        try{
            $task =  $this->taskService->showOneTask($id);
            return ApiResponse::success($task, "Display Task successfully");
        } catch (\Exception $e) {
            return ApiResponse::failure("Task Display failed");
        }
    }

    public function destroy($id): JsonResponse
    {
        try{
            $this->taskService->deleteTask($id);
            return ApiResponse::success([], "Task was deleted successfully");
        } catch (\Exception $e) {
            return ApiResponse::failure("Task deletion failed");
        }
    }


}
