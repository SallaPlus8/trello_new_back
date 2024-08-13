<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workspaces;
use Illuminate\Http\Request;
use App\Http\Requests\StWorkspaceRequest;
use App\Http\Requests\UpWorkspaceRequest;
use App\Http\Requests\AsssinUserWorkspace;
use Carbon\Carbon;

class WorkspaceController extends Controller
{
    protected static $model = Workspaces::class;

    public function index()
    {
        $result = self::$model::with('boards')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'success get data',
            'result' => $result
        ]);
    }

    public function show($id)
    {
        $result =  self::$model::find($id);
        if(!$result) {
            return response()->json([
                'success' => false,
                'message' => 'data not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'success get data',
            'result' => $result
        ]);
    }

    public function store(StWorkspaceRequest $request)
    {
        $validated = $request->all();

        $result =  self::$model::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'success store data',
            'result' => $result
        ]);

    }

    public function update(UpWorkspaceRequest $request , $id)
    {
        $validated = $request->all();

        $result =  self::$model::find($id);
     
        $result->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'success update data',
            'result' => $result
        ]);
    }

    public function delete($id)
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

    public function assingnUserToWorkspace(AsssinUserWorkspace $request) 
    {
        $validated = $request->validated();

        $workspace = self::$model::find($validated['workspace_id']);

        $users = User::whereIn('id',$validated['user_id'])->pluck('id');

        foreach($users as $user)
        {
            $workspace->users()->attach($user,['added_at' => Carbon::now()]);
        }


        return response()->json([
            'success' => true,
            'message' => 'success',
            'result' => $workspace->load('users')
        ]);


    }
}
