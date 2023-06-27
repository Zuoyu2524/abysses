/**
 * Resource for Abysses jobs.
 *
 * let resource = biigle.$require('abysses.api.maiaJob');
 *
 * Create a MAIA job:
 * resource.save({id: volumeId}, {
 *     clusters: 5,
 *     patch_size: 39,
 *     ...
 * }).then(...);
 *
 * Get all training proposals of a job:
 * resource.getTrainingProposals({id: 1}).then(...);
 *
 * Delete a MAIA job:
 * resource.delete({id: 1}).then(...);
 *
 * @type {Vue.resource}
 */

export default Vue.resource('api/v1/abysses-jobs{/id}', {}, {
    save: {
        method: 'POST',
        url: 'api/v1/volumes{/id}/abysses-jobs',
    },
    getTrainingProposals: {
        method: 'GET',
        url: 'api/v1/abysses-jobs{/id}/training-proposals',
    },
});
