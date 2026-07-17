<?php

use App\Models\User;

test('log in a user', function () {
    $user = User::factory()->create([
        'password' => 'password@123',
    ]);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'password@123')
        ->click('@login-button')
        ->assertPathIs('/ideas');

    $this->assertAuthenticated();
    expect(Auth::user())->toMatchArray([
        'name' => $user->name,
        'email' => $user->email,
    ]);
});
