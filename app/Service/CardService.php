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
            'the_list_id' => $validated['the_list_id'],
            'text' => $validated['text'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return $card;
    }

}
