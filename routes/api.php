<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ListController;
use App\Http\Controllers\Api\WorkspaceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

require __DIR__ . '/Workspace.php';


// Route::group(['middleware' => 'auth:sanctum'], function(){

    Route::post('logout', [AuthController::class,'logout']);

    // Route::post('/test', function () {

    //     return Auth::user()->hasPermission('update-roles');

    // });

    Route::group(['prefix' => 'roles'], function(){

        Route::get('get-roles', [RoleController::class,'getRoles']);

        Route::post('create', [RoleController::class,'create']);

        Route::post('update', [RoleController::class,'update']);

        Route::post('destroy', [RoleController::class,'destroy']);

    });

    Route::group(['prefix' => 'users'], function(){

        Route::get('get-users', [UserController::class,'getUsers']);

        Route::post('create', [UserController::class,'create']);

        Route::post('update', [UserController::class,'update']);

        Route::post('destroy', [UserController::class,'destroy']);

    });
    Route::group(['prefix' => 'workspaces'], function(){

        Route::get('get-workspaces', [WorkspaceController::class,'index']);

        Route::get('get-workspace/{card_id}', [WorkspaceController::class,'show']);

        Route::post('create', [WorkspaceController::class,'store']);

        Route::post('update', [WorkspaceController::class,'update']);

        Route::delete('destroy/{workspace_id}', [WorkspaceController::class,'destroy']);

        Route::post('/assign-user-to-workspace', [WorkspaceController::class, 'assignUserToWorkspace']);


    });
    Route::group(['prefix' => 'boards'], function(){

        Route::get('get-boards', [BoardController::class,'index']);

        Route::get('get-board/{board_id}', [BoardController::class,'show']);

        Route::post('create', [BoardController::class,'create']);

        Route::post('update', [BoardController::class,'update']);

        Route::delete('destroy/{board_id}', [BoardController::class,'destroy']);

        Route::post('/assign-user-to-board', [BoardController::class, 'assignUserToBoard']);

    });

    Route::group(['prefix' => 'lists'], function(){

        Route::get('get-lists', [ListController::class,'index']);

        Route::get('get-list/{list_id}', [ListController::class,'show']);

        Route::post('create', [ListController::class,'create']);

        Route::post('update', [ListController::class,'update']);

        Route::delete('destroy/{list_id}', [ListController::class,'destroy']);

    });

    Route::group(['prefix' => 'cards'], function(){

        Route::get('get-cards', [CardController::class,'index']);

        Route::get('get-card/{card_id}', [CardController::class,'show']);

        Route::post('create', [CardController::class,'create']);

        Route::post('update', [CardController::class,'update']);

        Route::delete('destroy/{card_id}', [CardController::class,'destroy']);

    });
    Route::group(['prefix' => 'comments'], function(){

        Route::get('get-comments', [CommentController::class,'index']);

        Route::get('get-comment/{comment_id}', [CommentController::class,'show']);

        Route::post('create', [CommentController::class,'store']);

        Route::post('update', [CommentController::class,'update']);

        Route::delete('destroy/{comment_id}', [CommentController::class,'destroy']);

    });


// });


Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);
