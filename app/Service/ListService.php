<?php

namespace App\Service;

use App\Models\TheList;

class ListService 
{   
    protected static $model = TheList::class;

    
    public function index()
    {   

        return $lists = self::$model::with('board')->get();
    }


    public function create($request)
    {
        $validated = $request->validated();
    
        $list =  self::$model::create($validated);

        return $list;
    }

    public function show($list_id)
    {
        return self::$model::with('board')->find($list_id);
    }

    public function update($request)
    {
        $validated = $request->validated();

        $list = self::$model::find($validated['list_id']);

        $list->update([
            'title' => $validated['title'],
            'board_id' => $validated['board_id'],
        ]);

        

        return $list;

    }


}
