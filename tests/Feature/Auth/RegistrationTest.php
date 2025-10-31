<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('new users can register with valid data', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser123',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'requested_role' => 'sales',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'username' => 'testuser123',
        'email' => 'test@example.com',
        'status' => 'pending',
        'requested_role' => 'sales',
    ]);
});

test('username validation fails for invalid formats', function (array $invalidUsername) {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => $invalidUsername['username'],
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'requested_role' => 'sales',
    ]);

    $response->assertSessionHasErrors(['username']);
    $response->assertSessionHasErrors(['username' => $invalidUsername['error']]);
})->with([
    ['username' => 'abc', 'error' => 'Username hanya boleh mengandung huruf, angka, dan underscore (_), minimal 5 dan maksimal 20 karakter.'],
    ['username' => 'abcdefghijklmnopqrstu', 'error' => 'Username hanya boleh mengandung huruf, angka, dan underscore (_), minimal 5 dan maksimal 20 karakter.'],
    ['username' => '123456', 'error' => 'Username tidak boleh hanya angka atau diawali/diakhiri dengan underscore (_).'],
    ['username' => '_testuser', 'error' => 'Username tidak boleh hanya angka atau diawali/diakhiri dengan underscore (_).'],
    ['username' => 'testuser_', 'error' => 'Username tidak boleh hanya angka atau diawali/diakhiri dengan underscore (_).'],
    ['username' => 'admin', 'error' => 'Username tidak boleh menggunakan kata terlarang seperti admin, root, atau system.'],
    ['username' => 'root', 'error' => 'Username tidak boleh menggunakan kata terlarang seperti admin, root, atau system.'],
    ['username' => 'system', 'error' => 'Username tidak boleh menggunakan kata terlarang seperti admin, root, atau system.'],
]);

test('password validation fails for invalid formats', function (array $invalidPassword) {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser123',
        'email' => 'test@example.com',
        'password' => $invalidPassword['password'],
        'password_confirmation' => $invalidPassword['password'],
        'requested_role' => 'sales',
    ]);

    $response->assertSessionHasErrors(['password']);
    $response->assertSessionHasErrors(['password' => $invalidPassword['error']]);
})->with([
    ['password' => 'short', 'error' => 'Password minimal 6 karakter.'],
    ['password' => str_repeat('a', 65), 'error' => 'Password maksimal 64 karakter.'],
]);



test('username must be unique', function () {
    // Create a user first
    \App\Models\User::create([
        'name' => 'Existing User',
        'username' => 'existinguser',
        'email' => 'existing@example.com',
        'password' => bcrypt('Password123!'),
        'status' => 'approved',
        'requested_role' => 'sales',
    ]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'existinguser',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'requested_role' => 'sales',
    ]);

    $response->assertSessionHasErrors(['username' => 'Username sudah digunakan.']);
});

test('email must be unique', function () {
    // Create a user first
    \App\Models\User::create([
        'name' => 'Existing User',
        'username' => 'existinguser',
        'email' => 'existing@example.com',
        'password' => bcrypt('Password123!'),
        'status' => 'approved',
        'requested_role' => 'sales',
    ]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser123',
        'email' => 'existing@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'requested_role' => 'sales',
    ]);

    $response->assertSessionHasErrors(['email' => 'Email sudah terdaftar.']);
});
