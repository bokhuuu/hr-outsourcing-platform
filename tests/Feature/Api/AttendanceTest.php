<?php

use App\Models\User;
use App\Models\Company;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows employee to check in', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);
    $user = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/attendances/check-in');

    $response->assertStatus(201);
    expect($response->json('data'))->toHaveKey('check_in_time');

    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employee->id,
        'date' => today()->toDateString(),
    ]);
});

it('prevents employee from checking in twice on same day', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);
    $user = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'company_id' => $company->id,
        'date' => today(),
        'check_in_time' => '09:00:00',
        'check_out_time' => null,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/attendances/check-in');

    $response->assertStatus(400);
    expect($response->json())->toHaveKey('error');
});

it('allows employee to check out after checking in', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);
    $user = User::factory()->create(['role' => 'employee']);
    $employee = Employee::factory()->create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'company_id' => $company->id,
        'date' => today(),
        'check_in_time' => '09:00:00',
        'check_out_time' => null,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/attendances/check-out');

    $response->assertStatus(200);
    expect($response->json('data'))->toHaveKey('check_out_time');

    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employee->id,
        'date' => today()->toDateString(),
    ]);

    $attendance = Attendance::where('employee_id', $employee->id)
        ->where('date', today())
        ->first();
    expect($attendance->check_out_time)->not->toBeNull();
});

it('prevents employee from checking out without checking in', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);
    $user = User::factory()->create(['role' => 'employee']);
    Employee::factory()->create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/attendances/check-out');

    $response->assertStatus(400);
    expect($response->json())->toHaveKey('error');
});

it('shows employee only their own attendance records', function () {
    $company = Company::factory()->create();
    $position = Position::factory()->create(['company_id' => $company->id]);

    $user1 = User::factory()->create(['role' => 'employee']);
    $employee1 = Employee::factory()->create([
        'user_id' => $user1->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);
    Attendance::factory()->create(['employee_id' => $employee1->id, 'company_id' => $company->id]);

    $user2 = User::factory()->create(['role' => 'employee']);
    $employee2 = Employee::factory()->create([
        'user_id' => $user2->id,
        'company_id' => $company->id,
        'position_id' => $position->id,
    ]);
    Attendance::factory()->create(['employee_id' => $employee2->id, 'company_id' => $company->id]);

    $response = $this->actingAs($user1, 'sanctum')
        ->getJson('/api/v1/attendances');

    $response->assertStatus(200);
    $attendances = $response->json('data');
    expect($attendances)->toHaveCount(1);
    expect($attendances[0]['employee_id'])->toBe($employee1->id);
});
