<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\ExcelRequest;
use App\Jobs\ProcessExcelUpload;
use App\Services\ExcelUpload\ExcelUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExcelUploadController extends Controller
{
    protected ExcelUploadService $excelUploadService;
    public function __construct(ExcelUploadService $excelUploadService)
    {
        $this->excelUploadService = $excelUploadService;
    }
    public function uploadExcel(ExcelRequest $request): JsonResponse
    {


        $filePath = $request->file('file')->store('imports', 'public');
        $logFileName = 'tms_' . now()->timestamp . '.json';
        $logPath = 'logs/' . $logFileName;

        if (!Storage::disk('public')->exists('logs')) {
            Storage::disk('public')->makeDirectory('logs');
        }

        ProcessExcelUpload::dispatch($filePath, $logPath, $this->excelUploadService);

        return ApiResponse::success([
            'log_url' => url('/storage/' . $logPath),
        ], "File uploaded successfully. Processing in background.");
    }
}
