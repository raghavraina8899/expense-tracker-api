<?php

namespace App\Http\Requests;

use App\Models\Expense;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseRequest extends FormRequest
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
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'amount' => ['sometimes', 'required', 'numeric', 'decimal:0,2', 'gt:0'],
            'category' => ['sometimes', 'required', Rule::in(Expense::CATEGORIES)],
            'payment_method' => ['sometimes', 'required', Rule::in(Expense::PAYMENT_METHODS)],
            'expense_date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'receipt_no' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('expenses', 'receipt_no')->ignore($this->route('expense'))],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
