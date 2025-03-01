<?php

namespace App\Services\ExcelUpload;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ExcelUploadValidatorService
{
    public function validate(array $row): array
    {
        $errors = [];

        if (!isset($row['title'])) {
            $errors[] = 'Missing required field: title';
        }

        if (!isset($row['description'])) {
            $errors[] = 'Missing required field: description';
        }

        if (!isset($row['due_date'])) {
            $errors[] = 'Missing required field: due_date.';
        } else {
            $dueDate = $row['due_date'];

            if (is_numeric($dueDate)) {
                $dueDate = Carbon::createFromTimestamp(($dueDate - 25569) * 86400)->format('Y-m-d');
            }

            // YYYY-MM-DD format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dueDate)) {
                $errors[] = "Invalid due_date format. Expected YYYY-MM-DD, got '{$row['due_date']}'.";
                Log::error("Invalid date format: " . $row['due_date']);
            }
        }


        if (!isset($row['due_date'])) {
            $errors[] = 'Missing required field: due_date.';
        }
        if (!isset($row['priority'])) {
            $errors[] = 'Missing required field: priority.';
        }


        $validPriorities = ['Low', 'Medium', 'High'];
        if (isset($row['priority']) && !in_array($row['priority'], $validPriorities)) {
            $errors[] = "Invalid priority value: {$row['priority']}. Allowed values: Low, Medium, High.";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
