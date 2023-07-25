<?php

namespace Biigle\Modules\abysses\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Biigle\Modules\abysses\AbyssesTest;
use Biigle\Volume;
use Queue;
use Illuminate\Http\Request;
use DB;
use File;
use Biigle\Image;
use League\Csv\Writer;

class FileController extends Controller
{
    public function downloadFile(Request $request, $jobId)
    {
        $job = AbyssesJob::findOrFail($jobId);
        $this->authorize('access', $job);
        $volume = $job->volume;

        $images = Image::select('filename')
            ->where('volume_id', $volume->id)
            ->get()
            ->toArray();

        $tests = AbyssesTest::select('id', 'label', 'image_id')
            ->where('job_id', $jobId)
            ->get()
            ->toArray();

        // Prepare the CSV data
        $csvData = [];
        foreach ($tests as $index => $test) {
            $csvData[$index]['id'] = $test['id'];
            $csvData[$index]['jobId'] = $jobId;
            $csvData[$index]['images->id'] = $test['image_id']; // Fix variable name
            $csvData[$index]['images->filename'] = $images[$index]['filename'];
            $csvData[$index]['labels'] = $test['label']; // Fix variable name
        }

        // Create a CSV file using the league/csv package
        $csv = Writer::createFromString('');
        // Check if $csvData is not empty
        if (!empty($csvData)) {
            // Insert headers if $csvData has elements
            $csv->insertOne(array_keys($csvData[0]));
        }
        $csv->insertAll($csvData);
        $csvContent = $csv->getContent();

        // Generate a unique file name or use a predefined file name
        $fileName = 'Abysses_label_recognition_results_' . $jobId . '.csv';

        // Store the CSV content in a temporary file
        $path = config('abysses.tmp_dir') . $fileName;

        File::put($path, $csvContent);

        // Set the appropriate headers for the CSV file download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // Return the file download response
        return response()->download($path, $fileName, $headers);
    }

}
