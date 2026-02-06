<?php

use App\Models\SystemLogo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a successful response', function () {
    SystemLogo::create([
        'filename' => 'test-logo.png',
        'is_active' => true,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
});
