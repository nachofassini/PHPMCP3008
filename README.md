# MCP3008 PHP library
[![Latest Stable Version](https://img.shields.io/packagist/v/volantus/mcp3008.svg)](https://packagist.org/packages/volantus/mcp3008)

PHP library for reading values from the A/D converter MCP3008

## Dependencies
The library requires the [berry-spi](https://github.com/Volantus/berry-spi) extension to be installed

## Installation
The library may be installed using Composer:
```bash
composer require volantus/mcp3008
```

## Usage
All measurements are done through the Reader class.

The constructor requires an SpiInterface object and the ADC reference voltage, which is 3.3 per default on Raspberry.

If you want to use the native SPI channels (recommended), you may use the RegularInterface:

```PHP
use Volantus\BerrySpi\RegularInterface;
use Volantus\MCP3008\Reader;

$spiInterface = new RegularInterface(1, 32000, 0);
$reader = new Reader($spiInterface, 3.3);
```

If you want to use any other GPIO Pins instead, please use the BitBangingInterface:


```PHP
use Volantus\BerrySpi\BitBangingInterface;
use Volantus\MCP3008\Reader;

$spiInterface = new BitBangingInterface(12, 16, 20, 21, 32000, 0);
$reader = new Reader($spiInterface, 3.3);
```

Reading values is done by channel and value is returned as Measurement object:
```PHP
// Reading value of ADC channel 4
$value = $reader->read(4);

// Getting the raw value, e.g. 789
$value->getRawValue();

// Getting the calculated voltage depending on reference voltage
// e.g. 2.54V in case of 3.3V ref. voltage 
$value->calculateVoltage();
```

## SPI Opening/Closing behaviour
Per default the reader opens the SPI interface in the constructor and closes it in the destructor.
But only if needed, so you are free to control it by yourself:
```PHP
$spiInterface = new RegularInterface(1, 32000, 0);
$spiInterface->open();

$reader = new Reader($spiInterface, 3.3);

$spiInterface->close();
unset($reader);
```

# Contribution
Contribution in form of bug reports, suggestions or pull requests is highly welcome!
