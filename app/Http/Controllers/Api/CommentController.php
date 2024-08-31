<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    protected static $model = Comment::class;

    public function index()
    {
        $result = self::$model::with('card')->get();

        return response()->json([
            'success' => true,
            'message' => 'success get data',
            'result' => $result
        ]);
    }

    public function show($id)
    {
        $result = self::$model::find($id);
        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'data not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'success get data',
            'result' => $result
        ]);
    }

    public function store(StoreCommentRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('comment-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $result = self::$model::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'success store data',
            'result' => $result
        ]);
    }

    public function update(UpdateCommentRequest $request)
    {
        $data = $request->except('comment_id');

        $result = self::$model::find($request->comment_id);
        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'data not found',
            ]);
        }
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($result->photo) {
                Storage::disk('public')->delete($result->photo);
            }
            $photoPath = $request->file('photo')->store('comment-photos', 'public');
            $data['photo'] = $photoPath;
        }

        $result->update($data);
        return response()->json([
            'success' => true,
            'message' => 'success update data',
            'result' => $result
        ]);
    }

    public function destroy($id)
    {
        $result = self::$model::find($id);
        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'data not found',
            ]);
        }
   // Delete the photo file if it exists
        if ($result->photo) {
            Storage::disk('public')->delete($result->photo);
        }

        $result->delete();
        return response()->json([
            'success' => true,
            'message' => 'success deleted data',
            'result' => $result
        ]);
    }
}
