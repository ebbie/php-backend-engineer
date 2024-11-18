<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepaymentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'loan_id' => 'required',
                'amount' => 'required|numeric|min:0',
            ]);

            $loan = Loan::findOrFail($validated['loan_id']);

            $authUserId = Auth::id();
            if ($loan->borrower_id !== $authUserId) {
                return response()->json([
                    'error' => 'Unauthorized. Only the borrower can make payments for this loan.',
                ], 403);
            }

            $totalRepaid = Repayment::where('loan_id', $loan->id)->sum('amount');

            $remainingAmount = $loan->amount - $totalRepaid;

            if ($validated['amount'] > $remainingAmount) {
                return response()->json([
                    'error' => 'Repayment amount exceeds the remaining loan balance.',
                    'remaining_balance' => $remainingAmount,
                ], 400);
            }

            $repayment = Repayment::create([
                'loan_id' => $validated['loan_id'],
                'amount' => $validated['amount'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Repayment logged successfully.',
                'repayment' => $repayment,
                'remaining_balance' => $remainingAmount - $validated['amount'],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Server Error'], 500);
        }
    }
}
