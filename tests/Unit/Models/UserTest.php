<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\User;

test('it can have ideas', function () {
    $user = User::factory()->create();
    expect($user->ideas)->toBeEmpty();

    Idea::factory()->create(['user_id' => $user->id]);
    expect($user->fresh()->ideas)->toHaveCount(1);
});

test('it hashes the password', function () {
    $user = User::factory()->create(['password' => 'secret']);
    expect($user->password)->not->toBe('secret');
});

test('it hides password and remember token', function () {
    $user = User::factory()->create();
    expect($user->toArray())
        ->not->toHaveKeys(['password', 'remember_token']);
});

test('it uses uuids as primary key', function () {
    $user = User::factory()->create();
    expect($user->id)->toBeUuid();
});
