<?php

declare(strict_types=1);

use App\Http\Controllers\StepController;
use App\Models\Idea;
use App\Models\Step;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\patch;

mutates(StepController::class);

describe('update', function () {
    it('requires authentication', function () {
        $step = Step::factory()->create();

        patch(route('step.update', $step))->assertRedirect(route('login'));

        expect($step->fresh()->completed)->toBeFalse();
    });

    it('toggles the step completed state', function () {
        $user = User::factory()->create();
        $step = Step::factory()->for(Idea::factory()->for($user))->create();

        actingAs($user)
            ->from(route('idea.show', $step->idea))
            ->patch(route('step.update', $step))
            ->assertRedirect(route('idea.show', $step->idea));

        expect($step->fresh()->completed)->toBeTrue();

        actingAs($user)->patch(route('step.update', $step));

        expect($step->fresh()->completed)->toBeFalse();
    });

    it('disallows toggling a step of an idea you did not create', function () {
        $user = User::factory()->create();
        $step = Step::factory()->create();

        actingAs($user)
            ->patch(route('step.update', $step))
            ->assertForbidden();

        expect($step->fresh()->completed)->toBeFalse();
    });
});
