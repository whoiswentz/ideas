<?php

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
        ->click('Create')
        ->assertPathIs('/ideas');

    expect($user->ideas()->first())->toMatchArray([
        'title' => 'Some idea example',
        'status' => 'completed',
        'description' => 'Some idea example',
        'links' => [
            'https://example.com/ideas',
            'https://example2.com/ideas',
        ],
    ]);
});
