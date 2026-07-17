<?php

test('register a user', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'jonh@example.com')
        ->fill('password', 'password@123')
        ->click('@create-account-button')
        ->assertPathIs('/ideas');

    $this->assertAuthenticated();
    expect(Auth::user())->toMatchArray([
        'name' => 'John Doe',
        'email' => 'jonh@example.com',
    ]);
});

test('required a valid email address', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john')
        ->fill('password', 'password@123')
        ->click('@create-account-button')
        ->assertPathIs('/register');
});
