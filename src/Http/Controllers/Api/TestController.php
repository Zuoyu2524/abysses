<?php

namespace Biigle\Modules\abysses\Http\Controllers\Api;

use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\abysses\AbyssesJob;
use Biigle\Modules\abysses\AbyssesJobState as State;
use Biigle\Volume;
use Queue;
use Illuminate\Http\Request;
use Biigle\Project;
use Biigle\Role;
use DB;
use Biigle\Image;
use Symfony\Component\Process\Process;

class TestController extends Controller
{

    public function index(Request $request, $id)
    {
        $job = AbyssesJob::findOrFail($id);
        $this->authorize('access', $job);
        $volume = $job->volume;
        $state = State::pluck('id', 'name');
        
        $user = $request->user();
        
        $projectId = Project::inCommon($user, $volume->id, [
            Role::editorId(),
            Role::expertId(),
            Role::adminId(),
        ])->pluck('id');
                
        $images = Image::select('id', 'filename')
            ->where('volume_id', $volume->id)
            ->get();
            
        $images = json_decode($images, true);

        return response()->json(['result' => $images]);
        
    }
}
