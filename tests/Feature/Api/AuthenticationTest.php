<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('allows user to login with valid credentials and returns token', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200);
    expect($response->json())->toHaveKey('token');
    expect($response->json('user'))->toHaveKey('email');
});

it('returns error with invalid credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401);
    expect($response->json())->toHaveKey('message');
});

it('denies access to protected endpoints without token', function () {
    $response = $this->getJson('/api/v1/attendances');

    $response->assertStatus(401);
});
