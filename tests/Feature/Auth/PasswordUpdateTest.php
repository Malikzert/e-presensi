<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $user = User::factory()->create([
        'password' => bcrypt('Old-password123'), // Set password lama yang kuat
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/profile/edit')
        ->put('/password', [
            'current_password' => 'Old-password123',
            'password' => 'New-password123',
            'password_confirmation' => 'New-password123',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile/edit');

    $this->assertTrue(Hash::check('New-password123', $user->refresh()->password));
});


test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile/edit')
        ->put('/password', [
            'current_password' => 'password-salah-total-123', // Pastikan salah
            'password' => 'PasswordBaru123',
            'password_confirmation' => 'PasswordBaru123',
        ]);

    $response
        ->assertSessionHasErrorsIn('updatePassword', 'current_password')
        ->assertRedirect('/profile/edit');
});