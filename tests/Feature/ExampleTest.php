<?php

test('the application redirects the root path to ideas', function () {
    $response = $this->get('/');

    $response->assertRedirect('/ideas');
});
