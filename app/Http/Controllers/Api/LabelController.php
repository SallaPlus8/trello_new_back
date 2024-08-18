<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\AddCardRequest; // You can create a request similar to this for validation
use App\Http\Requests\Label\StoreLabelRequest; // Create a request for validation
use App\Http\Requests\Label\UpdateLabelRequest; // Create a request for validation
use App\Models\Card;
use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    protected static $model = Label::class;

    public function index($card_id)
    {
        $result = self::$model::where('card_id',$card_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Success fetching data',
            'result' => $result
        ]);
    }

    public function show($id)
    {
        $result = self::$model::find($id);
        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Success fetching data',
            'result' => $result
        ]);
    }

    public function store(StoreLabelRequest $request)
    {
        $validated = $request->all();

        $result = self::$model::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Success storing data',
            'result' => $result
        ]);
    }

    public function update(UpdateLabelRequest $request)
    {
        $validated = $request->except('label_id');

        $result = self::$model::find($request->label_id);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ]);
        }

        $result->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Success updating data',
            'result' => $result
        ]);
    }

    public function destroy($id)
    {
        $result = self::$model::find($id);
        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ]);
        }
        $result->delete();
        return response()->json([
            'success' => true,
            'message' => 'Success deleting data',
            'result' => $result
        ]);
    }
}
