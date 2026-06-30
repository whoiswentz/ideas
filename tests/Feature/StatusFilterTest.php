<?php

declare(strict_types=1);

use App\Enums\IdeaStatus;
use App\Models\Idea;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('renders a filter pill for every status', function () {
    $user = User::factory()->create();
    Idea::factory()->for($user)->create(['status' => IdeaStatus::COMPLETED]);

    $response = actingAs($user)->get(route('idea.index'))->assertOk();

    foreach (IdeaStatus::cases() as $case) {
        $response->assertSee('href="/ideas?status='.$case->value.'"', false);
        $response->assertSee($case->label());
    }
});

it('marks the selected filter pill as active by dropping btn-outlined', function () {
    $user = User::factory()->create();

    $html = actingAs($user)
        ->get(route('idea.index', ['status' => IdeaStatus::COMPLETED->value]))
        ->assertOk()
        ->getContent();

    preg_match('/<a[^>]*href="\/ideas\?status=completed"[^>]*\sclass="([^"]*)"/', $html, $active);
    expect($active)->not->toBeEmpty()
        ->and($active[1])->not->toContain('btn-outlined');

    preg_match('/<a[^>]*href="\/ideas\?status=pending"[^>]*\sclass="([^"]*)"/', $html, $inactive);
    expect($inactive)->not->toBeEmpty()
        ->and($inactive[1])->toContain('btn-outlined');
});
