<?php

namespace App\Helpers;
use Maatwebsite\Excel\Facades\Excel;
class ExcelHelper
{
    /**
     * Process the Excel file and return the data as an associative array.
     *
     * @param string $filePath
     * @return array
     */
    public static function processExcelFile(string $filePath): array
    {
        $collection = Excel::toCollection([], $filePath);
        $rows = $collection->first();

        if (!$rows || $rows->isEmpty()) {
            return []; // Return empty array if no rows found
        }

        // Get the headers (first row)
        $headers = $rows->first();

        // Convert headers to lowercase for normalization
        $normalizedHeaders = array_map('strtolower', $headers->toArray());

        // Skip the first row (header) and process the rest
        $data = $rows->skip(1);

        // Filter out completely empty rows
        $filteredData = $data->filter(function ($row) {
            return collect($row)->filter()->isNotEmpty(); // Ensure at least one non-empty cell
        });

        return $filteredData->map(function ($row) use ($normalizedHeaders) {
            return array_combine($normalizedHeaders, $row->toArray());
        })->toArray();
    }

}
