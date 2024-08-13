<?php

namespace App\Http\Controllers\Api;

use App\Models\Board;
use Illuminate\Http\Request;
use App\Service\BoardService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Board\AddBoardRequest;
use App\Http\Requests\Board\UpdateBoardRequest;

class BoardController extends Controller
{

    protected $boards;

    public function __construct(BoardService $boards)
    {
        $this->boards = $boards;
    }


    public function index()
    {
        $boards = $this->boards->index();

        return response()->json([
            'data'      => $boards,
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
                'success'   => false,
                'message' => "this board not found"

            ], 200);

        }

        return response()->json([
            'data'      => $board,
            'success'   => true

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
}
