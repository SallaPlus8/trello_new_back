<?php

namespace App\Service;

use App\Models\Card;
use Illuminate\Support\Facades\Storage;

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
        // Add the authenticated user's ID
        $validated['user_id'] = auth()->user()->id;

        // Check if a photo was uploaded
        if ($request->hasFile('photo')) {
            // Store the photo in the 'photos' directory and get the path
            $photoPath = $request->file('photo')->store('card-cover', 'public');

            // Add the photo path to the validated data
            $validated['photo'] = $photoPath;
        }

        // Create a new card with the validated data
        $card = self::$model::create($validated);

        return $card;
    }

    public function update($request)
    {
        // Find the existing card by its ID
        $card = self::$model::findOrFail($request->card_id);

        // Validate the incoming request data
        $data = $request->except('card_id');

        // Add the authenticated user's ID (if necessary for updates)
        $data['user_id'] = auth()->user()->id;

        // Check if a new photo was uploaded
        if ($request->hasFile('photo')) {
            // Store the new photo in the 'card-cover' directory and get the path
            $photoPath = $request->file('photo')->store('card-cover', 'public');

            // Optionally delete the old photo if it exists
            if ($card->photo) {
                Storage::disk('public')->delete($card->photo);
            }

            // Add the new photo path to the data data
            $data['photo'] = $photoPath;
        }

        // Update the card with the data data
        $card->update($data);

        return $card;
    }

}
