<?php

namespace Biigle\Modules\abysses\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\abysses\Http\Requests\DestroyAbyssesJob;
use Biigle\Modules\abysses\Http\Requests\StoreAbyssesJob;
use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Biigle\Volume;
use Queue;
use Biigle\Modules\abysses\AbyssesTest;

class AbyssesJobController extends Controller
{
    /**
     * Creates a new abysses job for the specified volume.
     *
     * @api {post} volumes/:id/maia-jobs Create a new abysses job
     * @apiGroup abysses
     * @apiName StoreabyssesJob
     * @apiPermission projectEditor
     * @apiDescription New MAIA jobs can only be created for image volumes without very large (tiled) images.
     *
     * @apiParam {Number} id The volume ID.
     *
     * @apiParam (Required parameters) {string} training_data_method One of `novelty_detection` (to perform novelty detection to generate training data), `own_annotations` (to use existing annotations of the same volume as training data), `knowledge_transfer` (to use knowlegde transfer based on distance to ground to get training data from another volume) or `area_knowledge_transfer` (to use knowlegde transfer based on image area to get training data from another volume).
     *
     * @apiParam (Required parameters for novelty detection) {number} nd_clusters Number of different kinds of images to expect. Images are of the same kind if they have similar lighting conditions or show similar patterns (e.g. sea floor, habitat types). Increase this number if you expect many different kinds of images. Lower the number to 1 if you have very few images and/or the content is largely uniform.
     * @apiParam (Required parameters for novelty detection) {number} nd_patch_size Size in pixels of the image patches used determine the training proposals. Increase the size if the images contain larger objects of interest, decrease the size if the objects are smaller. Larger patch sizes take longer to compute. Must be an odd number.
     * @apiParam (Required parameters for novelty detection) {number} nd_threshold Percentile of pixel saliency values used to determine the saliency threshold. Lower this value to get more training proposals. The default value should be fine for most cases.
     * @apiParam (Required parameters for novelty detection) {number} nd_latent_size Learning capability used to determine training proposals. Increase this number to ignore more complex objects and patterns.
     * @apiParam (Required parameters for novelty detection) {number} nd_trainset_size Number of training image patches used to determine training proposals. You can increase this number for a large volume but it will take longer to compute.
     * @apiParam (Required parameters for novelty detection) {number} nd_epochs Time spent on training when determining the training proposals.
     * @apiParam (Required parameters for novelty detection) {number} nd_stride A higher stride increases the speed of the novelty detection but reduces the sensitivity to small regions or objects.
     * @apiParam (Required parameters for novelty detection) {number} nd_ignore_radius Ignore training proposals or annotation candidates which have a radius smaller or equal than this value in pixels.
     *
     *
     * @apiParam (Optional parameters for existing annotations) {Array} oa_restrict_labels Array of label IDs to restrict the existing annotations to, which should be used as training proposals.
     * @apiParam (Optional parameters for existing annotations) {Boolean} oa_show_training_proposals If `true`, show the select training proposals stage with the existing annotations before continuing to the object detection stage.
     *
     * @apiParam (Required parameters for knowledge transfer) {number} kt_volume_id The ID of the volume from which to get the annotations for knowledge transfer.
     *
     * @apiParam (Optional parameters for knowledge transfer) {Array} kt_restrict_labels Array of label IDs to restrict the annotations of the other volume to, which should be used as training proposals.
     *
     * @param StoreMaiaJob $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAbyssesJob $request)
    {
        $job = new AbyssesJob;
        $job->volume_id = $request->volume->id;
        $job->user_id = $request->user()->id;
        $type = $request->input('type');
        $paramKeys = [
            'working_type',
        ];

        // Assign this early so we can use the shouldUse* methods below.
        $job->params = $request->only($paramKeys);
        
        if($type === "test") {
            $job->state_id = State::labelRecognitionId();
            $paramKeys = array_merge($paramKeys, [
                'kt_volume_id',
            ]);
        } else {
            $job->state_id = State::retrainingProposalsId();
            $paramKeys = array_merge($paramKeys, [
                'kt_volume_id',
            ]);
        }


        $job->params = $request->only($paramKeys);
        $job->save();

        if ($this->isAutomatedRequest()) {
            return $job;
        }

        echo($job->state_id);
        echo($type);

        return $this->fuzzyRedirect('abysses', $job->id);
    }

    /**
     * Delete a abysses job.
     *
     * @api {delete} maia-jobs/:id Delete a MAIA job
     * @apiGroup Maia
     * @apiName DestroyMaiaJob
     * @apiPermission projectEditor
     *
     * @apiParam {Number} id The job ID.
     *
     * @param DestroyMaiaJob $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyAbyssesJob $request)
    {
        $id = $request->job->id;
        $volumeId = $request->job->volume_id;
        AbyssesTest::where('job_id', $id)->delete();
        $request->job->delete();

        if (!$this->isAutomatedRequest()) {
            return $this->fuzzyRedirect('volumes-abysses', $volumeId)
                ->with('message', 'Job deleted')
                ->with('messageType', 'success');
        }
    }
}
