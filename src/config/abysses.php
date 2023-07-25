<?php

return [
    /*
    | Storage disk where the testing proposal patch images will be stored
    */
    'training_proposal_storage_disk' => env('ABYSSES_TRAINING_PROPOSAL_STORAGE_DISK'),

    /*
    | Maximum number of automatically generated training proposals that are created for
    | a job. This does not include any training proposals that were generated from
    | existing annotations. The limit applies to the list of training proposals sorted
    | by novelty score in descending order. Set to INF to allow any number.
    */
    'training_proposal_limit' => 50000,

    /*
    | Storage disk where the annotation candidate patch images will be stored
    */

    /*
    | Queue to submit new ABYSSES jobs to.
    */
    'request_queue' => env('ABYSSES_REQUEST_QUEUE', 'default'),

    /*
    | Queue connection to submit new ABYSSES jobs to.
    */
    'request_connection' => env('ABYSSES_REQUEST_CONNECTION', 'gpu'),

    /*
    | Queue to submit the result data of ABYSSES jobs to.
    */
    'response_queue' => env('_RESPONSE_QUEUE', 'default'),

    /*
    | Queue connection to submit the result data of ABYSSES jobs to.
    */
    'response_connection' => env('ABYSSES_RESPONSE_CONNECTION', 'gpu-response'),

    /*
    | Directory where the temporary files of label recofnition or object detection
    | should be stored.
    */
    'tmp_dir' => env('ABYSSES_TMP_DIR', storage_path('abysses_jobs')),

    /*
    | Keep the temporary files of a Abysses job in case of a failure.
    | For debugging purposes only.
    */
    'debug_keep_files' => env('ABYSSES_DEBUG_KEEP_FILES', false),

    /*
    | Path to the Python executable.
    */
    'python' => '/usr/bin/python3',

    /*
    | Number of worker threads to use during novelty detection or object detection.
    | Set this to the number of available CPU cores.
    */
    'max_workers' => env('ABYSSES_MAX_WORKERS', 1),

    /*
    | Path to the novelty detection script.
    */
    'label_recognition_script' => __DIR__.'/../resources/scripts/recognition/Main.py',

    /*
    | Number of 512x512 px images in a training batch of MMDetection.
    | This can be increased with larger GPU memory to achieve faster training.
    |
    | Default is 16.
    */
    'train_batch_size' => env('ABYSSES_TRAIN_BATCH_SIZE', 16),

    /*
     | Enable to disallow submission of new jobs.
     */
    'maintenance_mode' => env('ABYSSES_MAINTENANCE_MODE', false),
];
