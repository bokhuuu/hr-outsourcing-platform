<?php

use App\Models\User;
use App\Models\Company;
use App\Models\Position;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows employee to create leave request', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);
    $user = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/leave-requests', [
            'leave_type' => 'vacation',
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-05',
            'reason' => 'Family vacation',
        ]);

    $response->assertStatus(201);
    expect($response->json('data'))->toHaveKey('status');
    expect($response->json('data.status'))->toBe('pending');

    $this->assertDatabaseHas('leave_requests', [
        'employee_id' => $employee->id,
        'leave_type' => 'vacation',
        'status' => 'pending',
        'created_by' => $user->id,
    ]);
});

it('prevents employee from creating overlapping leave request', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);
    $user = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    LeaveRequest::factory()->create([
        'employee_id' => $employee->id,
        'company_id' => $company->id,
        'start_date' => '2026-02-01',
        'end_date' => '2026-02-05',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/leave-requests', [
            'leave_type' => 'sick',
            'start_date' => '2026-02-03',
            'end_date' => '2026-02-07',
            'reason' => 'Medical appointment',
        ]);

    $response->assertStatus(400);
    expect($response->json())->toHaveKey('error');
});

it('allows hr to approve leave request', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);
    $employeeUser = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create([
        'user_id' => $employeeUser->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    $leaveRequest = LeaveRequest::factory()->create([
        'employee_id' => $employee->id,
        'company_id' => $company->id,
        'status' => 'pending',
    ]);

    $hrUser = User::factory()->create(['role' => 'hr']);

    $response = $this->actingAs($hrUser, 'sanctum')
        ->putJson("/api/v1/leave-requests/{$leaveRequest->id}/approve");

    $response->assertStatus(200);
    expect($response->json('data.status'))->toBe('approved');

    $this->assertDatabaseHas('leave_requests', [
        'id' => $leaveRequest->id,
        'status' => 'approved',
        'approved_by' => $hrUser->id,
    ]);

    $updatedRequest = LeaveRequest::find($leaveRequest->id);
    expect($updatedRequest->approved_at)->not->toBeNull();
});

it('prevents employee from approving leave request', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);

    $user1 = User::factory()->create(['role' => 'employee']);
    $employee1 = Employee::factory()->create([
        'user_id' => $user1->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    $leaveRequest = LeaveRequest::factory()->create([
        'employee_id' => $employee1->id,
        'company_id' => $company->id,
        'status' => 'pending',
    ]);

    $user2 = User::factory()->create(['role' => 'employee']);
    Employee::factory()->create([
        'user_id' => $user2->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    $response = $this->actingAs($user2, 'sanctum')
        ->putJson("/api/v1/leave-requests/{$leaveRequest->id}/approve");

    $response->assertStatus(403);
});
