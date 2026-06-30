<?php

declare(strict_types=1);

use App\Enums\IdeaStatus;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('renders a status button with the test hook for every status', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('idea.index'))->assertOk();

    $response->assertSee('Status');
    $response->assertSee('name="status"', false);

    foreach (IdeaStatus::cases() as $case) {
        $response->assertSee('data-test="button-status-'.$case->value.'"', false);
    }
});

it('does not hardcode btn-outlined on the status buttons base class', function () {
    // The active-state outline is driven only by Alpine's :class binding; the
    // static class must stay free of btn-outlined so the selected button renders solid.
    $user = User::factory()->create();

    $html = actingAs($user)->get(route('idea.index'))->assertOk()->getContent();

    preg_match(
        '/<button[^>]*data-test="button-status-completed"[^>]*\sclass="([^"]*)"/',
        $html,
        $matches,
    );

    expect($matches)->not->toBeEmpty()
        ->and($matches[1])->not->toContain('btn-outlined');
});
