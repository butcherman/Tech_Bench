<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\TwoFactorConfirmedResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class TwoFactorConfirmedResponseUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | toResponse
    |---------------------------------------------------------------------------
    */
    public function test_to_response(): void
    {
        $testObj = new TwoFactorConfirmedResponse;
        $response = $testObj->toResponse($this->request());

        $this->assertEquals(302, $response->getStatusCode());
    }

    /*
    |---------------------------------------------------------------------------
    | Testing Methods
    |---------------------------------------------------------------------------
    */
    private function request(): Request
    {
        $request = Request::create('/login', 'POST');

        $request->setLaravelSession(
            app('session')->driver()
        );

        return $request;
    }
}
