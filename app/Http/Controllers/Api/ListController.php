<?php

namespace App\Http\Controllers\Api;

use App\Models\TheList;
use App\Service\ListService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\List\AddListRequest;
use App\Http\Requests\List\UpdateListRequest;

class ListController extends Controller
{
    protected $lists;

    public function __construct(ListService $lists)
    {
        $this->lists =  $lists;

        // $this->middleware('permission:read-lists')->only(['index', 'show']);
        // $this->middleware('permission:create-lists')->only('create');
        // $this->middleware('permission:update-lists')->only('update');
        // $this->middleware('permission:delete-lists')->only('delete');
    }

    // public function index()
    // {


    //     $lists = $this->lists->index();

    //     return response()->json([
    //         'data'      => $lists,
    //         'success'   => true

    //     ], 200);
    // }

    public function create(AddListRequest $request)
    {
        $list = $this->lists->create($request);

        return response()->json([
            'data'      => $list,
            'success'   => true

        ], 201);
    }

    // public function show($list_id)
    // {
    //     $list = $this->lists->show($list_id);

    //     if (!$list) {
    //         return response()->json([
    //             'data'      => [],
    //             'success'   => false,
    //             'message'   => "item not found",

    //         ], 200);
    //     }
    //     return response()->json([
    //         'data'      => $list,
    //         'success'   => true

    //     ], 200);
    // }

    public function update(UpdateListRequest $request)
    {
        $list = $this->lists->update($request);

        return response()->json([
            'data'      => $list,
            'success'   => true

        ], 202);
    }

    public function destroy($list_id)
    {
        $list = TheList::find($list_id);

        if (!$list) {

            return response()->json([
                'success'   => false,
                'message' => "this board not found"

            ], 203);
        }

        $list->delete();

        return response()->json([
            'success'   => true,
            'message'   => "deleted successfully"

        ], 203);
    }
}
