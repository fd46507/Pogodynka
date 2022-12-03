<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Weather;

class WeatherTest extends TestCase
{
    public function dataGetTemperatureFarenheit(): array
    {
        return [
            [25, 77],
            [20, 68],
            [17, 62],
            [32, 89],
            [0, 32]
        ];
    }

    /**
     * @dataProvider dataGetTemperatureFarenheit
     */
    public function testGetTemperatureFarenheit($key, $expectedMessage): void
    {
        $weather = new Weather();
        $weather->setTemperature($key);
        $message = $weather->getTemperatureFarenheit();

        $this->assertEquals($expectedMessage, $message, 'Mismatch in expected results!');
    }
}
