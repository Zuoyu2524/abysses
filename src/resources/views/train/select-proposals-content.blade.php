<proposals-image-grid
    v-show="hasProposals"
    :images="proposalsForSelectView"
    :selected-proposal-ids="selectedProposalIds"
    empty-url="{{ asset(config('thumbnails.empty_url')) }}"
    :width="{{config('thumbnails.width')}}"
    :height="{{config('thumbnails.height')}}"
    :selectable="isInTrainingProposalState"
    selected-icon="plus"
    listener-set="select-proposals"
    v-on:select="handleSelectedProposal"
    ref="proposalsImageGrid"></proposals-image-grid>
<div v-if="!loading && !hasProposals" class="abysses-content-message">
    <div class="text-warning">
        There are no training proposals.
    </div>
</div>
