<?php

namespace App\Http\Controllers\Api;

use App\Models\Board;
use Illuminate\Http\Request;
use App\Service\BoardService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Board\AddBoardRequest;
use App\Http\Requests\Board\AssignUserBoard;
use App\Http\Requests\Board\UpdateBoardRequest;
use App\Http\Resources\BoardResource;
use App\Http\Resources\CardDetailsResource;
use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class BoardController extends Controller
{

    protected $boards;

    public function __construct(BoardService $boards)
    {
        $this->boards = $boards;
    }


    public function index($workspace_id)
    {
        $boards = $this->boards->index($workspace_id);

        return response()->json([
            'data'      => BoardResource::collection($boards),
            'success'   => true

        ], 200);
    }

    public function create(AddBoardRequest $request)
    {
        $board = $this->boards->create($request);

        $board->users()->attach(auth()->id());

        return response()->json([
            'data'      => $board,
            'success'   => true

        ], 201);
    }

    public function show($board_id)
    {
        $board = $this->boards->show($board_id);

        if (!$board) {
            return response()->json([
                'success' => false,
                'message' => 'Board not found or you do not have access',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Board retrieved successfully',
            'data'      => new BoardResource($board),
        ], 200);

        }

    public function update(UpdateBoardRequest $request,$id)
    {
         $board = $this->boards->update($request,$id);

        return response()->json([

            'data'      => $board,

            'success'   => true

        ], 202);
    }

    public function destroy($board_id)
    {
        $board = Board::find($board_id);

        if(!$board) {

            return response()->json([
                'success'   => false,
                'message' => "this board not found"

            ], 200);
        }
        if ($board->photo) {
            Storage::disk('public')->delete($board->photo);
        }
        $board->delete();

        return response()->json([
            'success'   => true,
            'message'   => "deleted successfully"

        ], 200);
    }


    public function assignUserToBoard(AssignUserBoard $request)
    {
        $validated = $request->validated();

        $board = Board::find($validated['board_id']);

        $id_users = User::whereIn('id',$validated['user_id'])->pluck('id');

        foreach($id_users as $user_id)
        {
            $board->users()->attach($user_id);
        }


        return response()->json([
            'success' => true,
            'message' => 'success',
            'result' => $board->load('users')
        ]);


    }

    public function removeUserFromBoard(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'board_id' => 'required|exists:boards,id',
        'user_id' => 'required|exists:users,id',
    ]);

    // Find the board by its ID
    $board = Board::findOrFail($validated['board_id']);

    // Detach the user from the board
    $board->users()->detach($validated['user_id']);

    return response()->json([
        'success' => true,
        'message' => 'User removed from board successfully',
        // 'result' => $board->load('users')
    ]);
}


public function uploadPhoto(Request $request,$id)
{
    $validated = $request->validate([
        'photo' =>'required',
    ]);

    $board = Board::find($id);

    if ($request->hasFile('photo')) {
        if ($board->photo) {
            Storage::disk('public')->delete($board->photo);
        }

        $validated['photo'] = $request->file('photo')->store('boards', 'public');
    }
    $board->update($validated);

    return response()->json([

        'data'      => $board,

        'success'   => true

    ], 202);
}


public function archivedCards($board_id)
{
    $userId = auth()->user()->id;

    // Retrieve boards with trashed cards
    $boards = Board::where('id', $board_id)
        ->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with([
            'lists.cards' => function ($query) {
                $query->onlyTrashed(); // Filter to include only trashed cards
            },
            'users' // Eager load users relationship
        ])
        ->get();

    // Extract trashed cards from the retrieved boards
    $archivedCards = $boards->flatMap(function ($board) {
        return $board->lists->flatMap(function ($list) {
            return $list->cards;
        });
    })->filter(function ($card) {
        return $card->trashed(); // Filter to ensure we only get trashed cards
    });

    return response()->json([
        'data'      => CardDetailsResource::collection($archivedCards),
        'success'   => true,
        'message'   => 'Archived cards retrieved successfully'
    ], 200);
}

public function restoreArchivedCard($card_id)
{
    // Find the trashed card by its ID
    $card = Card::onlyTrashed()->find($card_id);

    if (!$card) {
        return response()->json([
            'success' => false,
            'message' => 'Archived card not found',
        ], 404);
    }

    // Restore the card
    $card->restore();

    return response()->json([
        'success' => true,
        'message' => 'Archived card restored successfully',
        'data' => new CardDetailsResource($card),
    ], 200);
}

public function forceDeleteCard($card_id)
{
    // Find the trashed card by its ID
    $card = Card::onlyTrashed()->find($card_id);

    if (!$card) {
        return response()->json([
            'success' => false,
            'message' => 'Archived card not found',
        ], 404);
    }

    // Permanently delete the card
    $card->forceDelete();

    return response()->json([
        'success' => true,
        'message' => 'Card permanently deleted',
    ], 200);
}
}
