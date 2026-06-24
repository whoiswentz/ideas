<?php

declare(strict_types=1);

use App\Enums\IdeaStatus;
use App\Http\Controllers\IdeaController;
use App\Models\Idea;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;

mutates(IdeaController::class);

describe('index', function () {
    it('requires authentication', function () {
        get(route('idea.index'))->assertRedirect(route('login'));
    });

    it('shows the authenticated user ideas with status counts', function () {
        $user = User::factory()->create();
        Idea::factory()->for($user)->count(2)->create();

        actingAs($user)
            ->get(route('idea.index'))
            ->assertOk()
            ->assertViewIs('idea.index')
            ->assertViewHas('ideas', fn ($ideas) => $ideas->count() === 2)
            ->assertViewHas('statusCounts', fn ($counts) => $counts->get('all') === 2);
    });

    it('only lists ideas belonging to the authenticated user', function () {
        $user = User::factory()->create();
        $own = Idea::factory()->for($user)->create();
        $other = Idea::factory()->for(User::factory())->create();

        actingAs($user)
            ->get(route('idea.index'))
            ->assertOk()
            ->assertViewHas('ideas', fn ($ideas) => $ideas->contains($own) && ! $ideas->contains($other));
    });

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
            ->get(route('idea.index', ['status' => 'not-a-status']))
            ->assertOk()
            ->assertViewHas('ideas', fn ($ideas) => $ideas->count() === 3);
    });
});

describe('show', function () {
    it('requires authentication', function () {
        $idea = Idea::factory()->create();

        get(route('idea.show', $idea))->assertRedirect(route('login'));
    });

    it('displays the idea', function () {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create();

        actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertOk()
            ->assertViewIs('idea.show')
            ->assertViewHas('idea', fn (Idea $viewIdea) => $viewIdea->is($idea));
    });
});

describe('destroy', function () {
    it('requires authentication', function () {
        $idea = Idea::factory()->create();

        delete(route('idea.destroy', $idea))->assertRedirect(route('login'));

        expect($idea->fresh())->not->toBeNull();
    });

    it('deletes the idea and redirects to the index', function () {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create();

        actingAs($user)
            ->delete(route('idea.destroy', $idea))
            ->assertRedirect(route('idea.index'));

        expect($idea->fresh())->toBeNull();
    });
});
