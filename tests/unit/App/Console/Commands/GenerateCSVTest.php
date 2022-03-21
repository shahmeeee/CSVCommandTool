<?php

namespace Tests\unit\App\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\GenerateCSV;
use Mockery\Mock;
use TestCase;

/**
 * @run php vendor/bin/codecept run unit App/Console/Commands/GenerateCSVTest.php --debug
 */
class GenerateCSVTest extends TestCase
{

    public function testHandleValidationErrorReturn()
    {
        $generateCSVMock = \Mockery::mock(GenerateCSV::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $generateCSVMock->shouldReceive('validateString')
            ->once()
            ->andReturnTrue();

        $this->assertNull($generateCSVMock->handle());
    }

    public function testHandleValidationOnSuccess()
    {
        $string = 'test';
        $generateCSVMock = \Mockery::mock(GenerateCSV::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $generateCSVMock->shouldReceive('validateString')
            ->once()
            ->andReturnFalse();

        $generateCSVMock->shouldReceive('option')
            ->withArgs(['string'])
            ->once()
            ->andReturn($string);

        $generateCSVMock->shouldReceive('capitalize')
            ->withArgs([$string, 'even'])
            ->once();

        $generateCSVMock->shouldReceive('capitalize')
            ->withArgs([$string, 'odd'])
            ->once();

        $generateCSVMock->shouldReceive('info')
            ->withArgs(['CSV created!'])
            ->once()
            ->andReturn();

        $this->assertNull($generateCSVMock->handle());
    }
}
