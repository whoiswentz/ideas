<?php

declare(strict_types=1);

use App\Enums\IdeaStatus;
use App\Models\Idea;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('filters ideas by a valid status', function () {
    $user = User::factory()->create();
    $pending = Idea::factory()->for($user)->create(['status' => IdeaStatus::PENDING]);
    $completed = Idea::factory()->for($user)->create(['status' => IdeaStatus::COMPLETED]);

    actingAs($user)
        ->get(route('idea.index', ['status' => IdeaStatus::PENDING->value]))
        ->assertOk()
        ->assertViewHas('ideas', fn ($ideas) => $ideas->contains($pending) && ! $ideas->contains($completed));
});

it('falls back to all ideas when status is invalid', function () {
    $user = User::factory()->create();
    Idea::factory()->for($user)->count(3)->create();

    actingAs($user)
        ->get(route('idea.index', ['status' => 'dasdasd']))
        ->assertOk()
        ->assertViewHas('ideas', fn ($ideas) => $ideas->count() === 3);
});
