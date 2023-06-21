<?php

namespace Biigle\Modules\abysses\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\abysses\Events\AbyssesJobContinued;
use Biigle\Modules\abysses\Http\Requests\ContinueAbyssesJob;
use Biigle\Modules\abysses\Http\Requests\UpdateTrainingProposal;
use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Biigle\Modules\abysses\AbyssesTrain;

class TrainingProposalController extends Controller
{
    /**
     * Get all training proposals of a MAIA job.
     *
     * @api {get} maia-jobs/:id/training-proposals Get training proposals
     * @apiGroup Maia
     * @apiName IndexMaiaTrainingProposals
     * @apiPermission projectEditor
     * @apiDescription The training proposals are ordered by descending score.
     *
     * @apiParam {Number} id The job ID.
     *
     * @apiSuccessExample {json} Success response:
     * [
     *     {
     *         "id": 1,
     *         "selected": false,
     *         "image_id", 123
     *     }
     * ]
     *
     * @param int $id Job ID
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $job = AbyssesJob::findOrFail($id);
        $this->authorize('access', $job);

        return $job->abyssestrain()->abyssestrainlabel()
            ->join('images', 'images.id', '=', 'abysses_training_labels.image_id')
            ->select(
                'abysses_train_labels.id',
                'abysses_train_labels.image_id',
                'images.uuid as uuid'
                'train_id'
            )
            ->get()
            ->toArray();
    }

    /**
     * Continue a MAIA job from training proposal selection and refinement to object detection.
     *
     * @api {post} maia-jobs/:id/training-proposals Submit training proposals
     * @apiGroup Maia
     * @apiName ContinueMaiaJob
     * @apiPermission projectEditor
     * @apiDescription A job can only be continued if it is in training proposal selection and refinement state, and if it has selected training proposals.
     *
     * @apiParam {Number} id The job ID.
     *
     * @param ContinueMaiaJob $request
     * @return \Illuminate\Http\Response
     */
    public function submit(ContinueAbyssesJob $request)
    {
        $request->job->state_id = State::objectDetectionId();
        $request->job->save();
        event(new MaiaJobContinued($request->job));

        if (!$this->isAutomatedRequest()) {
            return $this->fuzzyRedirect('maia', $request->job->id);
        }
    }

    /**
     * Update a training proposal.
     *
     * @api {put} maia/training-proposals/:id Update a training proposal
     * @apiGroup Maia
     * @apiName UpdateTrainingProposal
     * @apiPermission projectEditor
     *
     * @apiParam {Number} id The training proposal ID.
     * @apiParam (Attributes that can be updated) {Boolean} selected Determine whether the proposal has been selected by the user or not.
     * @apiParam (Attributes that can be updated) {Number[]} points Array containing three numbers representing the x- and y-coordinates as well as the radius of the training proposal circle.
     *
     * @param UpdateTrainingProposal $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTrainingProposal $request)
    {
        if ($request->filled('points')) {
            $request->proposal->points = $request->input('points');
            $disk = config('maia.training_proposal_storage_disk');
            GenerateImageAnnotationPatch::dispatch($request->proposal, $disk)
                ->onQueue(config('largo.generate_annotation_patch_queue'));
        }

        $request->proposal->selected = $request->input('selected', $request->proposal->selected);
        $request->proposal->save();
    }
}
