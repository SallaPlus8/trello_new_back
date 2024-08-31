<?php

namespace App\Service;

use App\Models\Board;
use Illuminate\Support\Facades\Storage;

class BoardService
{
    protected static $model = Board::class;


    public function index($workspace_id)
    {
        $userId = auth()->user()->id;

        return $boards = self::$model::where('workspace_id',$workspace_id)->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with([
            'lists.cards','users' // Eager load cards relationship for each list
        ])->get();
    }


    public function create($request)
    {
        $validated = $request->validated();
        if ($request->hasFile('photo')) {
            // Store the uploaded file and get its path
            $validated['photo'] = $request->file('photo')->store('boards', 'public');
        }
        $board =  self::$model::create($validated);

        return $board;
    }

    public function show($board_id)
    {
        $userId = auth()->user()->id;
        return $board = self::$model::where('id', $board_id)
        ->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with([
            'lists.cards','users' // Eager load cards relationship for each list
        ])
        ->first();
    }

    public function update($request,$id)
{
    $validated = $request->validated();

    $board = self::$model::find($id);

    if ($request->hasFile('photo')) {
        if ($board->photo) {
            Storage::disk('public')->delete($board->photo);
        }

        $validated['photo'] = $request->file('photo')->store('boards', 'public');
    }
    $board->update($validated);

    return $board;
}


}
