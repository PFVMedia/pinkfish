<?php

use App\Models\User;

it('shows the admin login page', function () {
    $this->get('/admin/login')->assertSuccessful();
});

it('redirects unauthenticated users from admin', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

it('allows admin to access the panel', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertSuccessful();
});

it('prevents non-admin from accessing the panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertForbidden();
});
