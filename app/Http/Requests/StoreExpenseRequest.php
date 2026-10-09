<?php

namespace App\Http\Requests;

use App\Models\Expense;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'decimal:0,2', 'gt:0'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'payment_method' => ['required', Rule::in(Expense::PAYMENT_METHODS)],
            'expense_date' => ['required', 'date', 'before_or_equal:today'],
            'receipt_no' => ['nullable', 'string', 'max:255', 'unique:expenses,receipt_no'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
