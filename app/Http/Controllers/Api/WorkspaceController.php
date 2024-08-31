<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\AssignUserWorkspace;
use App\Http\Requests\Workspace\StWorkspaceRequest;
use App\Http\Requests\Workspace\UpWorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WorkspaceController extends Controller
{
    protected static $model = Workspace::class;

    // public function index()
    // {
    //     $result = self::$model::with('boards')->get();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'success get data',
    //         'result' => $result
    //     ]);
    // }
    public function index()
    {
        $userId = auth()->user()->id;

        // Retrieve workspaces where the user is a member and also filter boards based on user membership
        $workspaces = self::$model::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['boards' => function ($query) use ($userId) {
            $query->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
        }])->with('users') // Eager load boards and filter based on user_id
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Success fetching data',
            'result' => WorkspaceResource::collection($workspaces)
        ]);
    }
    // public function show($id)
    // {
    //     $result =  self::$model::find($id);
    //     if(!$result) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'data not found',
    //         ]);
    //     }
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'success get data',
    //         'result' => $result
    //     ]);
    // }
    public function show($id)
    {
        $userId = auth()->user()->id;

        // Find the workspace by ID and check if the user is a member
        $workspace = self::$model::where('id', $id)
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['boards' => function ($query) use ($userId) {
                $query->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                });
            }])
            ->with('users')->first();

        if (!$workspace) {
            return response()->json([
                'success' => false,
                'message' => 'Workspace not found or you do not have access',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully fetched workspace data',
            'result' => new WorkspaceResource($workspace)
        ], 200);
    }
    public function store(StWorkspaceRequest $request)
    {
        $validated = $request->all();

        $result =  self::$model::create($validated);

        $result->users()->attach(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'success store data',
            'result' => $result
        ]);

    }

    public function update(UpWorkspaceRequest $request )
    {
        $validated = $request->all();

        $result =  self::$model::find($request->workspace_id);

        $result->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'success update data',
            'result' => $result
        ]);
    }

    public function destroy($id)
    {
        $result =  self::$model::find($id);
        if(!$result){
            return response()->json([
                'success' => false,
                'message' => 'data not found',
            ]);
        }
        $result->delete();
        return response()->json([
            'success' => true,
            'message' => 'success deleted data',
            'result' => $result
        ]);
    }

    public function assignUserToWorkspace(AssignUserWorkspace $request)
    {
        $validated = $request->validated();

        $workspace = self::$model::find($validated['workspace_id']);

        $id_users = User::whereIn('id',$validated['user_id'])->pluck('id');

        foreach($id_users as $user_id)
        {
            $workspace->users()->attach($user_id);
        }


        return response()->json([
            'success' => true,
            'message' => 'success',
            'result' => $workspace->load('users')
        ]);
    }

    public function removeUserFromWorkspace(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'workspace_id' => 'required|exists:workspaces,id',
        'user_id' => 'required|exists:users,id',
    ]);

    // Find the workspace by its ID
    $workspace = Workspace::findOrFail($validated['workspace_id']);

    // Detach the user from the workspace
    $workspace->users()->detach($validated['user_id']);

    return response()->json([
        'success' => true,
        'message' => 'User removed from workspace successfully',
        // 'result' => $workspace->load('users')
    ]);
}
}
