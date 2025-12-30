<?php

use App\Models\User;

test('home page can be visited by guests', function () {
    $response = $this->get(route('home'));
    $response->assertStatus(200);
});

test('authenticated users can visit the home page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('home'));
    $response->assertStatus(200);
});
