<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RepaymentTest extends TestCase
{
    use DatabaseTransactions;

    private User $borrower;
    private Loan $loan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->borrower = User::factory()->create();
        $this->loan = Loan::factory()->create([
            'borrower_id' => $this->borrower->id,
            'amount' => 1000,
        ]);
    }

    public function test_borrower_can_log_a_valid_repayment()
    {
        $this->actingAs($this->borrower);

        $response = $this->postJson('/api/repayment', [
            'loan_id' => $this->loan->id,
            'amount' => 500,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Repayment logged successfully.',
            ]);

        $this->assertDatabaseHas('repayments', [
            'loan_id' => $this->loan->id,
            'amount' => 500,
        ]);
    }

    public function test_repayment_cannot_exceed_remaining_loan_balance()
    {
        $this->actingAs($this->borrower);

        // Total loan amount is 1000; trying to repay more than the balance for this scenario
        $response = $this->postJson('/api/repayment', [
            'loan_id' => $this->loan->id,
            'amount' => 1500,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Repayment amount exceeds the remaining loan balance.',
            ]);

        $this->assertDatabaseMissing('repayments', [
            'loan_id' => $this->loan->id,
            'amount' => 1500,
        ]);
    }
}
