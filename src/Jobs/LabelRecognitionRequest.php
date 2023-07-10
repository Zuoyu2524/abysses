<?php

namespace Biigle\Modules\abysses\Jobs;

use Biigle\Modules\abysses\AbyssesJob;
use Exception;
use File;
use FileCache;
use Queue;

/**
 * This job is executed on a machine with GPU access.
 */
class LabelRecognitionRequest extends JobRequest
{
    /**
     * Disable the timeout of the Laravel queue worker because this job may run long.
     *
     * @var int
     */
    public $timeout = 0;

    /**
     * Execute the job
     */
    public function handle()
    {
        $this->createTmpDir();

        $images = $this->getGenericImages();

        FileCache::batch($images, function ($images, $paths) {
            $script = config('abysses.label_recognition_script');
            $path = $this->createInputJson($images, $paths);
            $this->python("{$script} {$path}");
        });

        $labels = $this->parseLabels($images);
        $this->dispatchResponse($labels);
        $this->cleanup();
    }

    /**
     * Create the JSON file that is the input to the novelty detection script.
     *
     * @param array $images GenericImage instances.
     * @param array $paths Paths to the cached image files.
     * @return string Input JSON file path.
     */
    protected function createInputJson($images, $paths)
    {
        $path = "{$this->tmpDir}/input.json";
        $imagesMap = [];
        foreach ($images as $index => $image) {
            $imagesMap[$image->getId()] = $paths[$index];
        }

        $content = [
            'images' => $imagesMap,
            'tmp_dir' => $this->tmpDir,
            'max_workers' => intval(config('abysses.max_workers')),
            'ckp_model' => intval(config('abysses.ckp_model'))
        ];

        File::put($path, json_encode($content, JSON_UNESCAPED_SLASHES));

        return $path;
    }

    /**
     * Dispatch the job to store the novelty detection results.
     *
     * @param array $labels
     */
    protected function dispatchResponse($labels)
    {
        $this->dispatch(new LabelRecognitionResponse($this->jobId, $labels));
    }

    /**
     * {@inheritdoc}
     */
    protected function dispatchFailure(Exception $e)
    {
        $this->dispatch(new LabelRecognitionFailure($this->jobId, $e));
    }

    /**
     * {@inheritdoc}
     */
    protected function getTmpDirPath()
    {
        return parent::getTmpDirPath()."-label-recognition";
    }
}
