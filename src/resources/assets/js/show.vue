<script>
import JobApi from './api/abyssesJob';
import {handleErrorResponse} from './import';
import {ImagesStore} from './import';
import {Keyboard} from './import';
import {LoaderMixin} from './import';
import {Messages} from './import';
import {SidebarTab} from './import';
import {Sidebar} from './import';

// recognition = Label Recognition
// proposals = Retraining Proposal

/**
 * View model for the main view of a Abysses job
 */
export default {
    mixins: [LoaderMixin],
    components: {
        sidebar: Sidebar,
        sidebarTab: SidebarTab,
    },
    data() {
        return {
            job: null,
            states: null,
            labelTrees: [],
            openTab: 'info',
        };
    },
    computed: {
        infoTabOpen() {
            return this.openTab === 'info';
        },
        selectProposalsTabOpen() {
            return this.openTab === 'select-proposals';
        },
        refineProposalsTabOpen() {
            return this.openTab === 'refine-proposals';
        },
        isInLabelRecognitionState() {
            return this.job.state_id === this.states['label-recognition'];
        },
        isInRetrainingProposalsState() {
            return this.job.state_id === this.states['retraining-proposals'];
        },
    },
    methods: {
        handleTabOpened(tab) {
            this.openTab = tab;
        },

        handleLoadingError(message) {
            Messages.danger(message);
        },
    },
    created() {
        this.job = biigle.$require('abysses.job');
        this.states = biigle.$require('abysses.states');
        this.labelTrees = biigle.$require('abysses.labelTrees');
    },
};
</script>