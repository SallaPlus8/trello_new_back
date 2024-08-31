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
use App\Models\CardDetail;
use App\Models\TheList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
    protected function logAction($card_id, $action)
    {
        CardDetail::create([
            'user_id' => Auth::id(),
            'desc' => $action,
            'card_id' => $card_id,
        ]);
    }



    public function create(AddCardRequest $request)
    {
        // return $request;

        $card = $this->cards->create($request);

        $this->logAction($card->id, ' created this card.');


        return response()->json([
            'data'      => $card,
            'success'   => true

        ], 201);
    }

    public function show($card_id)
    {
        $card = $this->cards->show($card_id);

        if (!$card) {

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

        $this->logAction($card->id,' updated this card.');

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

        $this->logAction($card_id, ' archived this card.');

        return response()->json([
            'success'   => true,
            'message'   => "Archived successfully"

        ], 203);
    }


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
            'photo' => 'nullable', // Validation rule for the photo
        ]);

        // If there's an existing photo, delete it
        if ($request->photo) {
            if ($card->photo) {
                Storage::disk('public')->delete($card->photo);
            }
            // Store the new photo
            $photoPath = $request->file('photo')->store('card-cover', 'public');
            // Update the card's photo path
            $card->photo = $photoPath ?? null;
        }
        // $card->color = $request->color ?? null;
        $card->save();

        $this->logAction($card_id,' updated the attachment photo of this card.');

        return response()->json([
            'success' => true,
            'message' => "data updated successfully",
            'photo_url' =>  $card->photo ? Storage::url($card->photo) : null,
            // 'color' => $card->color,
        ], 200);
    }

    public function updateColor(Request $request, $card_id)
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
            'color' => 'nullable', // Validation rule for the photo
        ]);

        $card->color = $request->color ?? null;
        $card->save();

        $this->logAction($card_id, ' updated the Color of this card.');

        return response()->json([
            'success' => true,
            'message' => "data updated successfully",
            // 'photo_url' =>  $card->photo ? Storage::url($card->photo): null,
            'color' => $card->color,
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

        $this->logAction($card_id, ' Deleted the attachment photo of this card.');

        return response()->json([
            'success' => true,
            'message' => "Photo deleted successfully",
        ], 200);
    }
    public function deleteColor($card_id)
    {
        $card = Card::find($card_id);

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => "Card not found",
            ], 404);
        }

        if (!$card->color) {
            return response()->json([
                'success' => false,
                'message' => "No color to delete",
            ], 404);
        }


        // Remove the photo path from the card
        $card->color = null;
        $card->save();

        $this->logAction($card_id, ' Deleted the color of this card.');

        return response()->json([
            'success' => true,
            'message' => "Color deleted successfully",
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

        $this->logAction(
            $card_id,
            ' updated the dates of this card. Start time: '
                . ($card->start_time ? $card->start_time : 'Not Set')
                . ', End time: '
                . ($card->end_time ? $card->end_time : 'Not Set')
        );
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

        $oldListName = $card->list->title;

        // Update the card with the new list_id and position
        $card->update([
            'the_list_id' => $validated['the_list_id'],
            'position' => $validated['position'],
        ]);
        $list=TheList::find($request->the_list_id);
       $newListName = $list->title;
        $user = auth()->user();

        $logMessage = "moved this card from list: {$oldListName} to list: {$newListName}";
        $card->details()->create([
            'user_id' => $user->id,
            'desc' => $logMessage,
            'card_id' => $card->id,
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

        // Get the original list name for logging
        $oldListName = $originalCard->list->title;

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
        $newListName = TheList::find($validated['the_list_id'])->title;

        // Log the action in card_details
        $user = auth()->user();
        $logMessage = "copied this card from list: {$oldListName} to list: {$newListName}";
        $newCard->details()->create([
            'user_id' => $user->id,
            'desc' => $logMessage,
            'card_id' => $newCard->id,
        ]);

        return response()->json([
            'data' => new CardDetailsResource($newCard->load('comments', 'labels')),
            'success' => true,
            'message' => 'Card copied successfully',
        ], 201);
    }

    public function updateDescPhoto(Request $request, $card_id)
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
            'description_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        // If there's an existing photo, delete it
        if ($card->description_photo) {
            Storage::disk('public')->delete($card->description_photo);
        }

        // Store the new photo
        $photoPath = $request->file('description_photo')->store('card-description-photo', 'public');

        // Update the card's photo path
        $card->description_photo = $photoPath;
        $card->save();
        $user = auth()->user();
        $logMessage = "updated the description photo for this card.";
        $card->details()->create([
            'user_id' => $user->id,
            'desc' => $logMessage,
            'card_id' => $card->id,
        ]);
        return response()->json([
            'success' => true,
            'message' => "Description photo updated successfully",
            'photo_url' => Storage::url($card->description_photo),
        ], 200);
    }
    public function deleteDescPhoto($card_id)
    {
        $card = Card::find($card_id);

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => "Card not found",
            ], 404);
        }

        if (!$card->description_photo) {
            return response()->json([
                'success' => false,
                'message' => "No photo to delete",
            ], 404);
        }

        // Delete the photo
        Storage::disk('public')->delete($card->description_photo);

        // Remove the photo path from the card
        $card->description_photo = null;
        $card->save();

        $user = auth()->user();
        $logMessage = "deleted the description photo from this card.";
        $card->details()->create([
            'user_id' => $user->id,
            'desc' => $logMessage,
            'card_id' => $card->id,
        ]);
        return response()->json([
            'success' => true,
            'message' => "Description photo deleted successfully",
        ], 200);
    }
}
