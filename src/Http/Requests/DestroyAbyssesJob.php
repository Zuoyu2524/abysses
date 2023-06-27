<?php

namespace Biigle\Modules\abysses\Http\Requests;

use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Illuminate\Foundation\Http\FormRequest;

class DestroyAbyssesJob extends FormRequest
{
    /**
     * The job to destroy
     *
     * @var AbyssesJob
     */
    public $job;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->job = AbyssesJob::findOrFail($this->route('id'));

        return $this->user()->can('destroy', $this->job);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->job->state_id === State::labelRecognitionId()) {
                $validator->errors()->add('id', 'The job cannot be deleted while the label recognition is running.');
            } elseif ($this->job->state_id === State::retrainingProposalsId()) {
                $validator->errors()->add('id', 'The job cannot be deleted while the label training task is running.');
            }
        });
    }
}
