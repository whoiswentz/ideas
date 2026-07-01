<?php

use App\Models\Idea;
use App\Models\User;

it('can create a idea', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/ideas')
        ->click('@create-idea-button')
        ->fill('title', 'Some idea example')
        ->click('@button-status-completed')
        ->fill('description', 'Some idea example')
        ->fill('@new-link', 'https://example.com/ideas')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'https://example2.com/ideas')
        ->click('@submit-new-link-button')
        ->fill('@new-step', 'Step 1')
        ->click('@submit-new-step-button')
        ->fill('@new-step', 'Step 2')
        ->click('@submit-new-step-button')
        ->click('Create')
        ->assertPathIs('/ideas');

    /** @var Idea $idea */
    $idea = $user->ideas()->first();

    expect($idea)
        ->toMatchArray([
            'title' => 'Some idea example',
            'status' => 'completed',
            'description' => 'Some idea example',
            'links' => [
                'https://example.com/ideas',
                'https://example2.com/ideas',
            ],
        ])
        ->and($idea->steps)->toHaveCount(2);
});
