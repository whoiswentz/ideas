<?php

use App\Models\User;

test('log out a user', function () {
    $user = User::factory()->create([
        'password' => 'password@123',
    ]);
    $this->actingAs($user);

    visit('/')->click('@logout-button');

    $this->assertGuest();
});
