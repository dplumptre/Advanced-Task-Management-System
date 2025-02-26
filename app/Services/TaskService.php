<?php

namespace App\Services;

use App\Models\Task;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskService
{

    /**
     * @throws Exception
     */
    public function createTask(array $data): Task
    {
        try {
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $data['image_url'] = $this->uploadImage($data['image']);
            }
            return Task::query()->create($data);
        } catch (Exception $e) {
            Log::error("Error creating task ".$e->getMessage());
            throw new Exception("Error creating task: " . $e->getMessage());
        }
    }


    /**
     * @throws Exception
     */
    public function updateTask(int $id, array $data): Task
    {
        try {
            $task = Task::findOrFail($id);
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $this->deleteTaskImage($task);
            }
            $data['image_url'] = $this->uploadImage($data['image']);
            $task->update($data);
            return $task;
        } catch (Exception $e) {
            Log::error("Error Updating task " . $e->getMessage());
            throw new Exception("Error updating task: " . $e->getMessage());
        }
    }



    public function showOneTask(int $id): Task
    {
        try {
            return Task::findOrFail($id);
        } catch (Exception $e) {
            Log::error("Error Showing task ".$e->getMessage());
            throw new Exception("Error Showing task: " . $e->getMessage());
        }
    }


    /**
     * @throws Exception
     */
    public function deleteTask(int $id): void
    {
        try {
            $task = Task::findOrFail($id);
            $this->deleteTaskImage($task);
            $task->delete();
        } catch (Exception $e) {
            Log::error("Error Deleting task ".$e->getMessage());
            throw new Exception("Error Deleting task: " . $e->getMessage());
        }
    }



    private function uploadImage(UploadedFile $image): string
    {
        $imageName = 'tms'. '-' .time() . uniqid() . '.' . $image->getClientOriginalExtension();
        return $image->storeAs('tasks', $imageName, 'public');
    }


    private function deleteTaskImage($task):void
    {
        if ($task->image_url && Storage::disk('public')->exists($task->image_url)) {
            Storage::disk('public')->delete($task->image_url);
        }
    }

}
