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
        ->click('Create')
        ->assertPathIs('/ideas');

    expect($user->ideas()->first())->toMatchArray([
        'title' => 'Some idea example',
        'status' => 'completed',
        'description' => 'Some idea example'
    ]);
});
