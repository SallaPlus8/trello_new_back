<?php

namespace App\Http\Controllers\Api;

use App\Models\Board;
use Illuminate\Http\Request;
use App\Service\BoardService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Board\AddBoardRequest;
use App\Http\Requests\board\AssignUserBoard;
use App\Http\Requests\Board\UpdateBoardRequest;
use App\Http\Resources\BoardResource;
use App\Models\User;

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

    public function update(UpdateBoardRequest $request)
    {
        return $board = $this->boards->update($request);

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
}
