<?php

namespace App\Service;

use App\Models\Card;
use App\Models\CardDetail;
use App\Models\TheList;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CardService
{
    protected static $model = Card::class;

    protected function logAction($card_id, $action)
    {
        CardDetail::create([
            'user_id' => Auth::id(),
            'desc' => $action,
            'card_id' => $card_id,
        ]);
    }

    public function index()
    {
        return $cards = self::$model::with('list')->get();
    }
    public function show($card_id)
    {
        return $card = self::$model::with(['comments','labels','details'])->find($card_id);
    }
    public function create($request)
    {
        $validated = $request->validated();
        // Add the authenticated user's ID
        $validated['user_id'] = auth()->user()->id;


        if (!isset($validated['position'])) {
            $position= TheList::find($validated['the_list_id'])->cards()->count()+1;
            // $position=self::$model::count()+1;

            $validated['position'] =  $position;
        }
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

        $originalData = $card->toArray();

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

        $this->logSpecificChanges($card->id, $originalData, $data);

        return $card;
    }

    protected function logSpecificChanges($cardId, $originalData, $newData)
    {
        $changes = [];

        foreach ($newData as $key => $value) {
            // Handle cases where the original value is null and the new value is not
            if (!isset($originalData[$key]) && $newData[$key] !== null) {
                if ($key === 'start_time') {
                    $formattedValue = Carbon::parse($value)->addDay()->format('Y-m-d');
                    $changes[] = 'Date changed from \'null\' to \'' . $formattedValue . '\'';
                } elseif ($key === 'description') {
                    $changes[] = 'Description changed from \'null\'';
                } else {
                    $changes[] = ucfirst($key) . " changed from 'null' to '{$value}'";
                }
            }

            // Handle cases where the original value is different from the new value
            if (isset($originalData[$key]) && $originalData[$key] !== $value) {
                if ($key === 'start_time') {
                    $formattedOriginal = $originalData[$key] ? Carbon::parse($originalData[$key])->addDay()->format('Y-m-d') : 'null';
                    $formattedNew = Carbon::parse($value)->addDay()->format('Y-m-d');
                    $changes[] = 'Date changed from \'' . $formattedOriginal . '\' to \'' . $formattedNew . '\'';
                } elseif ($key === 'description') {
                    $changes[] = 'Description was updated';
                } else {
                    $changes[] = ucfirst($key) . " changed from '{$originalData[$key]}' to '{$value}'";
                }
            }
        }

        // If there were any changes, log them
        if (!empty($changes)) {
            $changeLog = implode(', ', $changes);
            $this->logAction($cardId, "updated the card: {$changeLog}");
        }
    }

}
