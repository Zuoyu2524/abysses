@if ($job->state_id === \Biigle\Modules\abysses\AbyssesJobState::labelRecognitionId())
    <strong class="text-warning">running test for label recognition</strong>
@elseif ($job->state_id === \Biigle\Modules\abysses\AbyssesJobState::failedLabelRecognitionId())
    <strong class="text-danger">failed test for label recognition</strong>
@elseif ($job->state_id === \Biigle\Modules\abysses\AbyssesJobState::retrainingProposalsId())
    <strong class="text-warning">running retraining for label recognition</strong>
@elseif ($job->state_id === \Biigle\Modules\abysses\AbyssesJobState::failedRetrainingProposals())
    <strong class="text-warning">failed running retraining for label recognition</strong>
@else
    <strong class="text-success">finished task label recognition</strong>
@endif
