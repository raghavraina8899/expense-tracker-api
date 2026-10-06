<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    public const CATEGORIES = ['food', 'travel', 'bills', 'shopping', 'health', 'other'];

    public const PAYMENT_METHODS = ['cash', 'upi', 'card', 'bank_transfer'];

    protected $fillable = ['title', 'amount', 'category', 'payment_method', 'receipt_no', 'expense_date', 'notes'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
        ];
    }
}
