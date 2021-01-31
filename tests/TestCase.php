<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function mockDependence(string $class, Closure $function, bool $makePartial = false)
    {
        $mock = Mockery::mock($class);

        if ($makePartial) {
            $this->app->instance($class, $function($mock->makePartial()));

            return;
        }

        $this->app->instance($class, $function($mock));
    }
}
