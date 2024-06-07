<?php

use App\Models\User;

it('does not create a to-do without a name field', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $response = $this->postJson('/categories', []);
    $response->assertStatus(422);
});
