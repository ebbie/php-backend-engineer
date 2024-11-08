<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use App\Policies\LoanPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoanControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for authentication if needed
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_returns_all_loans()
    {
        Loan::factory()->count(5)->create();

        $response = $this->getJson('/api/loans');

        $response->assertStatus(200);
    }

    public function test_successfully_creates_a_new_loan()
    {
        $loanData = [
            'amount' => 50000,
            'interest_rate' => 15,
            'duration_in_months' => 36,
            'lender_id' => $this->user->id,
            'borrower_id' => User::factory()->create()->id,
        ];

        $response = $this->postJson('/api/loans', $loanData);

        $response->assertStatus(201);
        $response->assertJsonFragment(['amount' => 50000]);
        $this->assertDatabaseHas('loans', ['amount' => 50000]);
    }

    public function test_successfully_retrieves_a_specific_loan()
    {
        $loan = Loan::factory()->create();

        $response = $this->getJson("/api/loan/{$loan->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['duration_in_months' => $loan->duration_in_months]);
    }

    public function test_updates_a_loan_successfully()
    {
        $loan = Loan::factory()->create([
            'amount' => 10000,
            'interest_rate' => 10,
            'duration_in_months' => 24,
        ]);

        $updateData = [
            'amount' => 20000,
            'interest_rate' => 12,
            'duration_in_months' => 36,
        ];

        $this->mock(LoanPolicy::class, function ($mock) {
            $mock->shouldReceive('update')->andReturn(true);
        });

        $response = $this->putJson("/api/loan/edit/{$loan->id}", $updateData);

        $response->assertStatus(200);
        $this->assertEquals('success', $response->getData());
        $this->assertDatabaseHas('loans', ['amount' => 20000]);
    }

    public function test_it_can_delete_a_loan()
    {
        $loan = Loan::factory()->create();

        $response = $this->deleteJson("/api/loan/{$loan->id}");

        $response->assertStatus(200);
        $response->assertJson(['id' => $loan->id, 'deleted' => true]);
        $this->assertSoftDeleted('loans', ['id' => $loan->id]);
    }
}
