<?php

use App\Models\User;

it('redirects unauthenticated users from admin to the unified login', function () {
    $this->get('/admin')->assertRedirect('/login');
});

it('allows admin to access the panel', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertSuccessful();
});

it('prevents non-admin from accessing the panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertForbidden();
});
