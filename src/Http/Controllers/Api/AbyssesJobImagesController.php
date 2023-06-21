<?php

namespace Biigle\Modules\abysses\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\abysses\AbyssesJob;

class AbyssesJobImagesController extends Controller
{
    /**
     * Get training proposal coordinates for an image.
     *
     * @api {get} maia-jobs/:jid/images/:iid/training-proposals Get training proposal coordinates
     * @apiGroup Maia
     * @apiName IndexMaiaImageTrainingProposals
     * @apiPermission projectEditor
     * @apiDescription Training proposals are assumed to have the circle shape. Returns a map of training proposal IDs to their points arrays.
     *
     * @apiParam {Number} jid The job ID.
     * @apiParam {Number} iid The image ID.
     *
     * @apiSuccessExample {json} Success response:
     * {
     *    "1": [19, 28, 37]
     * }
     *
     * @param int $jobId
     * @param int $imageId
     * @return \Illuminate\Http\Response
     */
    public function indexTrainingProposals($jobId, $imageId)
    {
        $job = AbyssesJob::findOrFail($jobId);
        $this->authorize('access', $job);

        return $job->retrainingProposals()
            ->where('image_id', $imageId)
            ->pluck('labels', 'id');
    }
}
