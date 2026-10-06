<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ExpenseController;

Route::prefix('v1')->group(function () {
    Route::get('/expenses', [ExpenseController::class, 'index']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show']);
    Route::match(['put', 'patch'], '/expenses/{expense}', [ExpenseController::class, 'update']);
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy']);
    Route::post('/expenses/{expense}/restore', [ExpenseController::class, 'restore'])->withTrashed();
});
