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

    protected function mockDependence(string $class, Closure $function)
    {
        $mock = Mockery::mock($class);

        $this->app->instance($class, $function($mock));
    }
}
