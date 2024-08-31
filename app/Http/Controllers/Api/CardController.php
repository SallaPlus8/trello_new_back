<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;

use App\Service\CardService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Card\AddCardRequest;
use App\Http\Requests\Card\AssignUserCard;
use App\Http\Requests\Card\UpdateCardRequest;
use App\Http\Resources\CardCustomResource;
use App\Http\Resources\CardDetailsResource;
use App\Http\Resources\CardResource;
use App\Models\Board;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    protected $cards;

    public function __construct(CardService $cards)
    {
        $this->cards =  $cards;

        // $this->middleware('permission:read-cards')->only(['index','show']);
        // $this->middleware('permission:create-cards')->only('create');
        // $this->middleware('permission:update-cards')->only('update');
        // $this->middleware('permission:delete-cards')->only('delete');
    }

    // public function index()
    // {
    //     $cards = $this->cards->index();

    //     return response()->json([
    //         'data'      => $cards,
    //         'success'   => true

    //     ], 200);
    // }


    public function create(AddCardRequest $request)
    {
        // return $request;

        $card = $this->cards->create($request);

        return response()->json([
            'data'      => $card,
            'success'   => true

        ], 201);
    }

    public function show($card_id)
    {
        $card = $this->cards->show($card_id);

        if(!$card) {

            return response()->json([
                'data'      => [],
                'success'   => false,
                'message'   => "item not found",

            ], 200);
        }

        return response()->json([
            'data'      => new CardDetailsResource($card),
            'success'   => true

        ], 200);
    }

    public function update(UpdateCardRequest $request)
    {
        $card = $this->cards->update($request);

        return response()->json([
            'data'      => $card,
            'success'   => true

        ], 200);
    }


    public function destroy($card_id)
    {
        $card = Card::find($card_id);

        if (!$card) {

            return response()->json([
                'success'   => false,
                'message' => "this card not found"

            ], 203);
        }
        // if ($card->photo) {
        //     // Storage::disk('public')->delete($card->photo);
        // }
        $card->delete();

        return response()->json([
            'success'   => true,
            'message'   => "Archived successfully"

        ], 203);
    }



    // public function assignUserToCard(AssignUserCard $request)
    // {
    //     $validated = $request->validated();

    //     $card = Card::find($validated['card_id']);

    //     $id_users = User::whereIn('id',$validated['user_id'])->pluck('id');

    //     foreach($id_users as $user_id)
    //     {
    //         $card->users()->attach($user_id);
    //     }


    //     return response()->json([
    //         'success' => true,
    //         'message' => 'success',
    //         'result' => $card->load('users')
    //     ]);


    // }
            // card side edits
    public function updatePhoto(Request $request, $card_id)
    {
        $card = Card::find($card_id);
        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => "Card not found",
            ], 404);
        }
        // Validate the request
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        // If there's an existing photo, delete it
        if ($card->photo) {
            Storage::disk('public')->delete($card->photo);
        }

        // Store the new photo
        $photoPath = $request->file('photo')->store('card-cover', 'public');

        // Update the card's photo path
        $card->photo = $photoPath;
        $card->save();

        return response()->json([
            'success' => true,
            'message' => "Photo updated successfully",
            'photo_url' => Storage::url($card->photo),
        ], 200);
    }
    public function deletePhoto($card_id)
    {
        $card = Card::find($card_id);

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => "Card not found",
            ], 404);
        }

        if (!$card->photo) {
            return response()->json([
                'success' => false,
                'message' => "No photo to delete",
            ], 404);
        }

        // Delete the photo
        Storage::disk('public')->delete($card->photo);

        // Remove the photo path from the card
        $card->photo = null;
        $card->save();

        return response()->json([
            'success' => true,
            'message' => "Photo deleted successfully",
        ], 200);
    }

    public function editDates(Request $request, $card_id)
{
    $card = Card::find($card_id);

    if (!$card) {
        return response()->json([
            'success' => false,
            'message' => "Card not found",
        ], 404);
    }

    // Validate the request
    $request->validate([
        'start_time' => 'nullable|date',
        'end_time' => 'nullable|date|after_or_equal:start_time',
    ]);

    // Update the card's start and end times
    $card->start_time = $request->start_time;
    $card->end_time = $request->end_time;
    $card->save();

    return response()->json([
        'success' => true,
        'message' => "Dates updated successfully",
        'data' => [
            'start_time' => $card->start_time,
            'end_time' => $card->end_time,
        ],
    ], 200);
}
public function move(Request $request, $card_id)
{
    // Validate the incoming request
    $validated = $request->validate([
        'the_list_id' => 'required|exists:the_lists,id',
        'position' => 'required|integer|min:1',
    ]);

    // Find the card by its ID
    $card = Card::findOrFail($card_id);

    // Update the card with the new list_id and position
    $card->update([
        'the_list_id' => $validated['the_list_id'],
        'position' => $validated['position'],
    ]);

    return response()->json([
        'data' => new CardCustomResource($card),
        'success' => true,
        'message' => 'Card moved successfully',
    ], 200);
}

public function copy(Request $request, $card_id)
{
    $validated = $request->validate([
        'the_list_id' => 'required|exists:the_lists,id',
        'position' => 'required|integer|min:0',
    ]);

    $originalCard = Card::findOrFail($card_id);

    $newCard = $originalCard->replicate();
    $newCard->the_list_id = $validated['the_list_id'];
    $newCard->position = $validated['position'];
    $newCard->save(); // Save the new card

    foreach ($originalCard->comments as $comment) {
        $newCard->comments()->create([
            'comment' => $comment->comment,
        ]);
    }

    foreach ($originalCard->labels as $label) {
        $newCard->labels()->create([
            'hex_color' => $label->hex_color,
            'title' => $label->title,
        ]);
    }

    return response()->json([
        'data' => new CardDetailsResource($newCard->load('comments', 'labels')),
        'success' => true,
        'message' => 'Card copied successfully',
    ], 201);
}
}
