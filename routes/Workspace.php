
<?php

use App\Http\Controllers\WorkspaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'work-space',
    'middleware' => "auth:sanctum"
], function () {
    
    // Route::get('/', [WorkspaceController::class, 'index']);
    // Route::get('{id}', [WorkspaceController::class, 'show']);
    // Route::post('/store', [WorkspaceController::class, 'store']);
    // Route::post('/update/{id}', [WorkspaceController::class, 'update']);
    // Route::delete('/delete/{id}', [WorkspaceController::class, 'delete']);

    // Route::post('/assign-user-to-workspace', [WorkspaceController::class, 'assingnUserToWorkspace']);
});