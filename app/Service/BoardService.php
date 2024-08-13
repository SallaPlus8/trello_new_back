<?php

namespace App\Service;

use App\Models\Board;

class BoardService 
{   
    protected static $model = Board::class;

    
    public function index()
    {
        return $boards = self::$model::get();
    }


    public function create($request)
    {
        $validated = $request->validated();
    
        $board =  self::$model::create($validated);

        return $board;
    }

    public function show($board_id)
    {
        return self::$model::find($board_id);
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
