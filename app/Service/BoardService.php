<?php

namespace App\Service;

use App\Models\Board;

class BoardService
{
    protected static $model = Board::class;


    public function index($workspace_id)
    {
        $userId = auth()->user()->id;

        return $boards = self::$model::where('workspace_id',$workspace_id)->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with([
            'lists.cards' // Eager load cards relationship for each list
        ])->get();
    }


    public function create($request)
    {
        $validated = $request->validated();

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
            'lists.cards' // Eager load cards relationship for each list
        ])
        ->first();
    }

    public function update($request)
    {
        $validated = $request->validated();

        $board = self::$model::find($validated['board_id']);

        $board->update([
            'name' => $validated['name'],
        ]);

        return $board;

    }


}
