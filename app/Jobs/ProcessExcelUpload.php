<?php

namespace App\Jobs;

use App\Helpers\ExcelHelper;
use App\Services\ExcelUpload\ExcelUploadService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessExcelUpload implements ShouldQueue
{
    use Queueable;

    private string $filePath;
    private string $logPath;
    private ExcelUploadService $excelUploadService;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $logPath, ExcelUploadService $excelUploadService)
    {
        $this->filePath = $filePath;
        $this->logPath = $logPath;
        $this->excelUploadService = $excelUploadService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Job started:');
            $fullPath = Storage::disk('public')->path($this->filePath);
            $mappedRows = ExcelHelper::processExcelFile($fullPath);
            $this->excelUploadService->process($mappedRows, $this->logPath);
            Storage::disk('public')->delete($this->filePath);
            Log::info('Excel processed: ');
        } catch (\Exception $e) {
            Log::error('Excel upload processing failed: ' . $e->getMessage());
        }
    }
}
