<h3>Create a new Abysses job <a class="btn btn-default btn-xs pull-right" href="{{route('manual-tutorials', ['abysses', 'about'])}}" title="More information on Abysses" target="_blank"><i class="fas fa-info-circle"></i></a></h3>
<p>
    You can choose to directly test the images using the default parameters to get the labels. Or manually annotate some of the images, again train the model and then test to get the image labels. The annotated labels in retrain task are very important, so please be careful to set the labels. Here we expect you to use the training function again to help us improve the performance of the network after using the test function to change the incorrect label results. Thank you very much for your correction.
</p>
<form id="abysses-job-form" method="POST" action="{{ url("api/v1/volumes/{$volume->id}/abysses-jobs") }}" v-on:submit="submit">
    @csrf
    <fieldset>
        <legend>Select working mode</legend>
            <select id="operation" name="operation">
            	    <option value="Please select a working mode">Select</option>
		    <option value="test">Test</option>
		    <option value="train">Train</option>
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
        @endif
        @if ($maintenanceMode && !$user->can('sudo'))
            <button type="submit" id="testButton" class="btn btn-success pull-right" disabled>Start Testing</button>
            <button type="submit" id="trainButton" class="btn btn-success pull-right" disabled>Start Training</button>
        @else
            <input type="hidden" name="type" v-bind:value="typeValue">
            <button type="submit" id="testButton" class="btn btn-success pull-right" :disabled="canSubmit" style="display: none;" v-on:click="setTypeValue('test')">Start Testing</button>
            <button type="submit" id="trainButton" class="btn btn-success pull-right" :disabled="canSubmit" style="display: none;" v-on:click="setTypeValue('train')">Start Training</button>
        @endif
    </div>
    <div></div>
    <br>
</form>

<script>
    var operationSelect = document.getElementById('operation');
    var testButton = document.getElementById('testButton');
    var trainButton = document.getElementById('trainButton');

    operationSelect.addEventListener('change', function() {
        var selectedOption = this.value; 

        if (selectedOption === 'test') {
            testButton.style.display = 'inline-block';
            trainButton.style.display = 'none';
        } else if (selectedOption === 'train') {
            testButton.style.display = 'none';
            trainButton.style.display = 'inline-block';
        } else {
            testButton.style.display = 'none';
            trainButton.style.display = 'none';
        }
    });
</script>

<script>
    new Vue({
        el: '#abysses-job-form',
        data: {
            typeValue: ''
        },
        methods: {
            setTypeValue(value) {
                this.typeValue = value;
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
