<?php

namespace App\Services\ExcelUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelUploadService
{

    private ExcelUploadValidatorService $validator;
    private TaskCreatorService $taskCreator;
    private array $logData = [];

    public function __construct(ExcelUploadValidatorService $validator, TaskCreatorService $taskCreator)
    {
        $this->validator = $validator;
        $this->taskCreator = $taskCreator;
    }

    public function process(array $rows, string $logPath): void
    {
        $successCount = 0;
        $failureCount = 0;

        foreach ($rows as $index => $row) {

            Log::info('Processing row ' . ($index + 1), $row);

            if (isset($row['due_date']) && is_numeric($row['due_date'])) {
                $row['due_date'] = Carbon::instance(Date::excelToDateTimeObject($row['due_date']))->format('Y-m-d');
            }

            $validationResult = $this->validator->validate($row);

            if ($validationResult['valid']) {
                Log::info('Row ' . ($index + 1) . ' is valid.', $row);
                $mappedRow = $this->mapRowToTaskData($row);
                ($this->taskCreator)($mappedRow);
                $successCount++;
            }else{
                $failureCount++;
                Log::warning('Row ' . ($index + 1) . ' has validation errors.', $validationResult['errors']);
            }

            $this->logData[] = [
                'row_number' => $index + 1,
                'row_data' => $row,
                'errors' => $validationResult['errors']
            ];

        }

        $summary = [
            'total_rows' => count($rows),
            'successful_rows' => $successCount,
            'failed_rows' => $failureCount
        ];

        $this->logData['summary'] = $summary;

        Log::info("path : ".$logPath );
        Log::info("full log",$this->logData );
        Storage::disk('public')->put($logPath, json_encode($this->logData, JSON_PRETTY_PRINT));

    }




    private function mapRowToTaskData(array $row): array
    {
        return [
            'title' => $row['title'] ?? null,
            'description' => $row['description'] ?? null,
            'due_date' =>  $row['due_date'] ?? null,
            'priority' => $row['priority'] ?? null,
        ];
    }


}
