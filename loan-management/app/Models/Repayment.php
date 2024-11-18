<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'loan_id'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
