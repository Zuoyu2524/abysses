<?php

namespace Biigle\Modules\abysses\Http\Controllers\Views;

use Biigle\Http\Controllers\Views\Controller;
use Biigle\ImageAnnotation;
use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Biigle\Project;
use Biigle\Role;
use Biigle\Volume;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;
use Biigle\LabelTree;
use Biigle\Image;

class AbyssesJobController extends Controller
{
    
    
    /**
     * Show the overview of abysses jobs for a volume
     *
     * @param Request $request
     * @param int $id Volume ID
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $volume = Volume::findOrFail($id);
        if (!$request->user()->can('sudo')) {
            $this->authorize('edit-in', $volume);
        }

        if (!$volume->isImageVolume() || $volume->hasTiledImages()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $jobs = AbyssesJob::where('volume_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        $hasJobsInProgress = $jobs
            ->whereIn('state_id', [
                State::labelRecognitionId(),
                State::retrainingProposalsId(),
            ])
            ->count() > 0;

        $hasJobsRunning = $jobs
            ->whereIn('state_id', [
                State::labelRecognitionId(),
                State::retrainingProposalsId(),
            ])
            ->count() > 0;

        $newestJobHasFailed = $jobs->isNotEmpty() ? $jobs[0]->hasFailed() : false;

        $maintenanceMode = config('abysses.maintenance_mode');

        return view('abysses::index', compact(
            'volume',
            'jobs',
            'hasJobsInProgress',
            'hasJobsRunning',
            'newestJobHasFailed',
            'maintenanceMode',
        ));
    }
    
    public function train(Request $request, $id)
    {
        $job = AbyssesJob::findOrFail($id);
        $this->authorize('access', $job);
        $volume = $job->volume;
        $states = State::pluck('id', 'name');
        
        $user = $request->user();
        
        if ($job->state_id === State::retrainingProposalsId()) {
            if ($user->can('sudo')) {
                // Global admins have no restrictions.
                $projectIds = $volume->projects()->pluck('id');
            } else {
                // Array of all project IDs that the user and the image have in common
                // and where the user is editor, expert or admin.
                $projectIds = Project::inCommon($user, $volume->id, [
                    Role::editorId(),
                    Role::expertId(),
                    Role::adminId(),
                ])->pluck('id');
            }
            
            $name = 'Abysses';
            
            $labels = LabelTree::select('id', 'version_id')
                ->with('labels', 'version')
                ->where('name', $name)
                ->get();
        }
        else
        {
            $labels = collect([]);
        }
        
        $tpUrlTemplate = Storage::disk(config('abysses.training_proposal_storage_disk'))
            ->url(':prefix/:id.'.config('largo.patch_format'));   
            
        $tpLimit = config('abysses.training_proposal_limit'); 
        
        $maintenanceMode = config('abysses.maintenance_mode');
        
        return view('abysses::train', compact(
            'job',
            'volume',
            'states',
            'labels',
            'tpUrlTemplate',
            'tpLimit',
            'maintenanceMode'
        ));
    }

}
