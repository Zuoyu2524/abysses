<h3>Create a new Abysses job <a class="btn btn-default btn-xs pull-right" href="{{ route('manual-tutorials', ['abysses', 'about']) }}" title="More information on Abysses" target="_blank"><i class="fas fa-info-circle"></i></a></h3>
<p>
    This abysses function help you to classify the images using the CNN Neural Networks. Please click on the pull-down menu to select "classify" and then click on the button "Start Classify" to create your job.
</p>
<form id="abysses-job-form" method="POST" action="{{ url("api/v1/volumes/{$volume->id}/abysses-jobs") }}" v-on:submit="submit">
    @csrf
    <input type="hidden" name="type" v-model="typeValue">
    <fieldset>
        <legend>Select working mode</legend>
        <select id="operation" name="operation" v-model="typeValue">
            <option value="">Please select a working mode</option>
            <option value="test">Classify</option>
        </select>
        <div class="form-group{{ $errors->has('volume') ? ' has-error' : '' }}">
            @if($errors->has('volume'))
                <span class="help-block">{{ $errors->first('volume') }}</span>
            @endif
            @if ($maintenanceMode)
                <div class="panel panel-warning">
                    <div class="panel-body text-warning">
                        ABYSSES is currently in maintenance mode and no new jobs can be submitted. Please come back later.
                    </div>
                </div>
            @else
                <button type="submit" id="testButton" class="btn btn-success pull-right" :disabled="canSubmit || !typeValue || typeValue === 'train'" v-show="typeValue && typeValue !== 'train'">Start Classify</button>            
            @endif
        </div>
        <div></div>
        <br>
    </fieldset>
</form>


<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script type="text/javascript">
    new Vue({
        el: '#abysses-job-form',
        data: {
            typeValue: '',
            canSubmit: false
        },
        methods: {
            submit() {
                this.canSubmit = true;
            }
        }
    });
</script>

@push('scripts')
<script type="text/javascript">
    biigle.$declare('abysses.volumeId', {!! $volume->id !!});
    biigle.$declare('abysses.hasErrors', {!! $errors->any() ? 'true' : 'false' !!});
</script>
@endpush
