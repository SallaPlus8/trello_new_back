<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;

use App\Service\CardService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Card\AddCardRequest;
use App\Http\Requests\Card\AssignUserCard;
use App\Http\Requests\Card\UpdateCardRequest;
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

    public function index()
    {
        $cards = $this->cards->index();

        return response()->json([
            'data'      => $cards,
            'success'   => true

        ], 200);
    }


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
            'data'      => $card,
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
        if ($card->photo) {
            Storage::disk('public')->delete($card->photo);
        }
        $card->delete();

        return response()->json([
            'success'   => true,
            'message'   => "deleted successfully"

        ], 203);
    }

    public function assignUserToCard(AssignUserCard $request)
    {
        $validated = $request->validated();

        $card = Card::find($validated['card_id']);

        $id_users = User::whereIn('id',$validated['user_id'])->pluck('id');

        foreach($id_users as $user_id)
        {
            $card->users()->attach($user_id);
        }


        return response()->json([
            'success' => true,
            'message' => 'success',
            'result' => $card->load('users')
        ]);


    }
}
