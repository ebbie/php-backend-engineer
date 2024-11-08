<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoanController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $loans = Loan::all();
        return response()->json($loans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration_in_months' => 'required|integer|min:1',
            'lender_id' => 'required|integer',
            'borrower_id' => 'required|integer',
        ]);
        // return $validatedData;
        $loan = Loan::create($validatedData);

        return response()->json($loan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $loan = Loan::where('id', $id)->get();
        
        return response()->json($loan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $loan = Loan::findOrFail($id);

        // Authorize the update action
        $this->authorize('update', $loan);

        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration_in_months' => 'required|integer|min:1'
        ]);

        $loan->update($validatedData);

        return response()->json('success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $loan = Loan::find($id);

        if ($loan === null) {
            return response()->json([
                'message' => "Loan with id {$id} not found",
                'status' => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$loan->delete()) {
            return response()->json([
                'message' => "Couldn't delete the loan with id {$id}",
                'status' => Response::HTTP_BAD_REQUEST,
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'id' => $id,
            'deleted' => true,
        ], Response::HTTP_OK);
    }

}
