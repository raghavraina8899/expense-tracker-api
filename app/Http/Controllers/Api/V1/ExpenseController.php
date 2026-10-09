<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        $expenses = Expense::orderBy('expense_date', 'desc')->orderBy('id', 'desc')->paginate($perPage);

        return $this->successResponse(
            ExpenseResource::collection($expenses),
            'Expenses retrieved successfully!',
            200,
            [
                'current_page' => $expenses->currentPage(),
                'per_page' => $expenses->perPage(),
                'total' => $expenses->total(),
                'last_page' => $expenses->lastPage(),
            ]
        );
    }

    public function show(Expense $expense): JsonResponse
    {
        return $this->successResponse(new ExpenseResource($expense->load('category')), 'Expense retrieved successfully!');
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $data = $request->validated();

        $expense = Expense::create($data);

        return $this->successResponse(new ExpenseResource($expense->load('category')), 'Expense created successfully!', 201);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): JsonResponse
    {
        $expense->update($request->validated());
        return $this->successResponse(new ExpenseResource($expense->load('category')), 'Expense updated successfully!');
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();
        return $this->successResponse(null, 'Expense deleted successfully!');
    }

    public function restore(Expense $expense): JsonResponse
    {
        if (!$expense->trashed()) {
            return $this->errorResponse('Expense is not deleted', null, 404);
        }

        $expense->restore();
        return $this->successResponse(new ExpenseResource($expense->load('category')), 'Expense restored successfully!');
    }
}
