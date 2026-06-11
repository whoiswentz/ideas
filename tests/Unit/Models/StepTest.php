<?php

declare(strict_types=1);

use App\Models\Idea;
use App\Models\Step;

test('it belongs to idea', function () {
    $step = Step::factory()->create();
    expect($step->idea)->toBeInstanceOf(Idea::class);
});

test('it is not completed by default', function () {
    $step = Step::factory()->create();
    expect($step->completed)->toBeFalsy();
});

test('it uses uuids as primary key', function () {
    $step = Step::factory()->create();
    expect($step->id)->toBeUuid();
});
