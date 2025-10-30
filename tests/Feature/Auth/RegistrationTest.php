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
    ['password' => 'short', 'error' => 'Password minimal 8 karakter.'],
    ['password' => str_repeat('a', 65), 'error' => 'Password maksimal 64 karakter.'],
    ['password' => 'password123!', 'error' => 'Password harus mengandung minimal 1 huruf besar dan 1 huruf kecil.'],
    ['password' => 'Password!', 'error' => 'Password harus mengandung minimal 1 angka.'],
    ['password' => 'Password123', 'error' => 'Password harus mengandung minimal 1 simbol spesial (!@#$%^&*).'],
    ['password' => '12345678', 'error' => 'Password harus mengandung minimal 1 huruf.'],
    ['password' => 'password123!', 'error' => 'Password harus mengandung minimal 1 huruf besar dan 1 huruf kecil.'],
    ['password' => 'Password123!', 'error' => 'Password harus mengandung minimal 1 angka.'],
    ['password' => 'Password123', 'error' => 'Password harus mengandung minimal 1 simbol spesial (!@#$%^&*).'],
    ['password' => '123456789', 'error' => 'Password tidak boleh menggunakan urutan umum seperti 123456, abcdef, password, atau qwerty.'],
    ['password' => 'abcdefghi', 'error' => 'Password tidak boleh menggunakan urutan umum seperti 123456, abcdef, password, atau qwerty.'],
    ['password' => 'password', 'error' => 'Password tidak boleh menggunakan urutan umum seperti 123456, abcdef, password, atau qwerty.'],
    ['password' => 'qwerty', 'error' => 'Password tidak boleh menggunakan urutan umum seperti 123456, abcdef, password, atau qwerty.'],
]);

test('password cannot contain username', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'testuser123!',
        'password_confirmation' => 'testuser123!',
        'requested_role' => 'sales',
    ]);

    $response->assertSessionHasErrors(['password' => 'Password tidak boleh mengandung username.']);
});

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
