<?php

namespace App\Service;

use App\Models\Card;

class CardService 
{   
    protected static $model = Card::class;


    public function index()
    {
        return $cards = self::$model::with('list')->get();
    }
    public function show($card_id)
    {
        return $card = self::$model::with('list')->find($card_id);
    }

    public function create($request)
    {
        $validated = $request->validated();

        $card = self::$model::create($validated);

        return $validated;
        
    }

    public function update($request)
    {
        $validated = $request->validated();

        $card = self::$model::find($validated['card_id']);

        $card->update([
            'text' => $validated['text'],
            'details' => $validated['details'],
            'list_id' => $validated['list_id']
        ]);

        return $card;
    }

}