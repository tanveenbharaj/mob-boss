<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Exception;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function disableProtectionHandling()
    {

        $this->app->instance(ExceptionHandler::class, new class extends Handler {

            public function __construct() {}

            public function report(Exception $e) {}

            public function render($request, Exception $e)
            {
                throw $e;
            }


        });

    }

    /**
     * Asserts that two given JSON encoded objects or arrays are equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     * @param string $message
     */
    public static function assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, $message = '')
    {
        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);

        $expected = json_decode($expectedJson);
        $actual   = json_decode($actualJson);

        static::assertNotEquals($expected, $actual, $message);
    }
}
