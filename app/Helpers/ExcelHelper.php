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

        // Get the first sheet from the collection
        // basically the first from the collection of rows
        $rows = $collection->first();

        // Get the headers (first row)
        $headers = $rows->first();

        // Convert the collection to an array and normalize the headers to lowercase
        $normalizedHeaders = array_map('strtolower', $headers->toArray());

        // Skip the first row (header) and process the rest
        $data = $rows->skip(1);  // Skip the header row

        // Map each row to an associative array using normalized headers
        return $data->map(function ($row) use ($normalizedHeaders) {
            // Convert row collection to array before using array_combine
            return array_combine($normalizedHeaders, $row->toArray());
        })->toArray();
    }
}
