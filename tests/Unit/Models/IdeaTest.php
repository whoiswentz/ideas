<?php

declare(strict_types=1);

use App\IdeaStatus;
use App\Models\Idea;
use App\Models\User;

test('it belongs to user', function () {
    $idea = Idea::factory()->create();
    expect($idea->user)->toBeInstanceOf(User::class);
});

test('it can have steps', function () {
    $idea = Idea::factory()->create();
    expect($idea->steps)->toBeEmpty();

    $idea->steps()->create(['description' => 'step 1']);
    expect($idea->fresh()->steps)->toHaveCount(1);
});

test('it has pending status by default', function () {
    $idea = Idea::factory()->create();
    expect($idea->status)->toBe(IdeaStatus::PENDING);
});

test('it casts status to enum', function () {
    $idea = Idea::factory()->create(['status' => 'completed']);
    expect($idea->status)->toBe(IdeaStatus::COMPLETED);
});

test('it uses uuids as primary key', function () {
    $idea = Idea::factory()->create();
    expect($idea->id)->toBeUuid();
});
