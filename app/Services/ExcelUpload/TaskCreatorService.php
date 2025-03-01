<?php

namespace App\Services\ExcelUpload;

use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\Log;

class TaskCreatorService
{


    /**
     * Invoke the class instance.
     */
    public function __invoke(array $data): void
    {
        try{
            Task::query()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'due_date' => $data['due_date'],
                 'priority' => $data['priority'],
            ]);
         } catch (Exception $e) {
            Log::error("Error Creating task on excel ".$e->getMessage());
        }
    }
}
