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
        
        AbyssesJob::where('volume_id', $id)->delete();
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
    
    /**
     * Show a MAIA job
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
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

            // All label trees that are used by all projects which are visible to the
            // user.
            $results = DB::table('labels AS l1')
           ->select('l1.id', 'l1.name', 'l2.name AS parent_name')
           ->join('label_trees', 'l1.label_tree_id', '=', 'label_trees.id')
           ->join('labels AS l2', 'l1.parent_id', '=', 'l2.id')
           ->where('label_trees.name', 'Abysses')
           ->whereNotNull('l1.parent_id')
           ->get();

           $i=0;
           $labels = array();
           $results = json_decode($results, true);
           foreach ($results as $label) {
               $key = $label["parent_name"];
               $value = $label["name"];
               if (array_key_exists($key, $labels)) {
                   $labels[$key][] = $value;
               } else {
                   $labels[$key] = [$value];
               }
           }
           
           $trees = LabelTree::select('id', 'version_id')
           ->with('labels', 'version')
           ->where('name', $name)
           ->get();


        } else {
            $labels = collect([]);
            $trees = collect([]);
        }

        $tpUrlTemplate = Storage::disk(config('abysses.training_proposal_storage_disk'))
            ->url(':prefix/:id.'.config('largo.patch_format'));

        $tpLimit = config('abysses.training_proposal_limit');

        return view('abysses::show', compact(
            'job',
            'volume',
            'states',
            'trees',
            'tpUrlTemplate',
            'tpLimit'
        ));
    }

    public function data(Request $request, $id)
    {
        $job = AbyssesJob::findOrFail($id);
        $this->authorize('access', $job);
        $volume = $job->volume;
        $states = State::pluck('id', 'name');

        
        return view('abysses::data', compact(
            'job',
            'volume',
            'states',
        ));
    }

}
