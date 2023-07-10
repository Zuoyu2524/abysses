<?php

namespace Biigle\Modules\abysses\Http\Requests;

use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Biigle\Volume;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreAbyssesJob extends FormRequest
{

    /**
     * The volume to create the abysses job for.
     *
     * @var Volume
     */
    public $volume;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->volume = Volume::findOrFail($this->route('id'));

        if (config('abysses.maintenance_mode')) {
            return $this->user()->can('sudo');
        }

        return $this->user()->can('edit-in', $this->volume);
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
            if (!$this->volume->isImageVolume()) {
                $validator->errors()->add('volume', 'ABYSSES is only available for image volumes.');
            }

            $hasJobInProgress = AbyssesJob::where('volume_id', $this->volume->id)
                ->whereIn('state_id', [
                    State::labelRecognitionId(),
                    State::retrainingProposalsId(),
                ])
                ->exists();

            if ($hasJobInProgress) {
                $validator->errors()->add('volume', 'A new ABYSSES job can only be sumbitted if there are no other jobs in progress for the same volume.');
            }

            if ($this->volume->hasTiledImages()) {
                $validator->errors()->add('volume', 'New ABYSSES jobs cannot be created for volumes with very large images.');
            }
        });
    }

    /**
     * Determine whether the volume contains images smaller than 512px.
     *
     * @param Volume $volume
     *
     * @return boolean
     */
    protected function hasSmallImages(Volume $volume)
    {
        return $volume->images()
            ->where(function ($query) {
                $query->whereNull('attrs->width')
                ->orWhereNull('attrs->height')
                ->orWhereRaw('("attrs"->>\'width\')::int < 512')
                ->orWhereRaw('("attrs"->>\'height\')::int < 512');
            })
            ->exists();
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        if (config('abysses.maintenance_mode')) {
            throw new AuthorizationException('ABYSSES is in maintenance mode and no new jobs can be submitted.');
        }

        return parent::failedAuthorization();
    }
}
