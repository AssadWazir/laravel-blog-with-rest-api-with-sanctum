<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF middleware for tests to avoid 419 errors when
        // submitting POST/PUT/DELETE requests in HTTP tests.
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // Ensure session is started and a CSRF token is available for test
        // utilities and any requests that rely on session tokens.
        if (function_exists('session')) {
            session()->start();
            session(['_token' => csrf_token()]);
            $this->withHeaders([
                'X-CSRF-TOKEN' => session('_token'),
            ]);
        }
    }
}
