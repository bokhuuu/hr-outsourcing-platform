<?php

use App\Models\User;
use App\Models\Company;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Absence;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows hr to register absence for employee', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);
    $employeeUser = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create([
        'user_id' => $employeeUser->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);
    $hrUser = User::factory()->create(['role' => 'hr']);

    $response = $this->actingAs($hrUser, 'sanctum')
        ->postJson('/api/v1/absences', [
            'employee_id' => $employee->id,
            'date' => '2026-01-15',
            'reason' => 'Sick leave',
        ]);

    $response->assertStatus(201);
    expect($response->json('data'))->toHaveKey('date');

    $this->assertDatabaseHas('absences', [
        'employee_id' => $employee->id,
        'date' => '2026-01-15',
        'created_by' => $hrUser->id
    ]);
});

it('prevents hr from registering duplicate absence for same employee and date', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);
    $employeeUser = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create([
        'user_id' => $employeeUser->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    $hrUser = User::factory()->create(['role' => 'hr']);

    Absence::factory()->create([
        'employee_id' => $employee->id,
        'company_id' => $company->id,
        'date' => '2026-01-15',
    ]);

    $response = $this->actingAs($hrUser, 'sanctum')
        ->postJson('/api/v1/absences', [
            'employee_id' => $employee->id,
            'date' => '2026-01-15',
            'reason' => 'Another reason',
        ]);

    $response->assertStatus(400);
    expect($response->json())->toHaveKey('error');
});

it('shows employee only their own absence records', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);

    $user1 = User::factory()->create(['role' => 'employee']);
    $employee1 = Employee::factory()->create([
        'user_id' => $user1->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);
    Absence::factory()->create([
        'employee_id' => $employee1->id,
        'company_id' => $company->id
    ]);

    $user2 = User::factory()->create(['role' => 'employee']);
    $employee2 = Employee::factory()->create([
        'user_id' => $user2->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);
    Absence::factory()->create([
        'employee_id' => $employee2->id,
        'company_id' => $company->id
    ]);

    $response = $this->actingAs($user1, 'sanctum')
        ->getJson('/api/v1/absences');

    $response->assertStatus(200);
    $absences = $response->json('data');
    expect($absences)->toHaveCount(1);
    expect($absences[0]['employee_id'])->toBe($employee1->id);
});

it('prevents employee from registering absence', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);

    $employeeUser = User::factory()->create(['role' => 'employee']);
    Employee::factory()->create([
        'user_id' => $employeeUser->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    $targetEmployeeUser = User::factory()->create(['role' => 'employee']);
    $targetEmployee = Employee::factory()->create([
        'user_id' => $targetEmployeeUser->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    $response = $this->actingAs($employeeUser, 'sanctum')
        ->postJson('/api/v1/absences', [
            'employee_id' => $targetEmployee->id,
            'date' => '2026-01-15',
            'reason' => 'Sick',
        ]);

    $response->assertStatus(403);
});
