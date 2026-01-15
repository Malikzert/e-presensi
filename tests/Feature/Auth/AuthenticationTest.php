<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    // Kita buat user dengan password yang spesifik memenuhi aturan (Huruf Besar + Angka)
    $user = User::factory()->create([
        'password' => bcrypt('New-password123')
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'New-password123', // Sesuaikan dengan password kuat
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('New-password123')
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});