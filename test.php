<?php
require_once __DIR__ . '/vendor/autoload.php';

use Volantus\BerrySpi\RegularInterface;
use Volantus\MCP3008\Reader;

$interface = new RegularInterface(0, 32000, 0);


$reader = new Reader($interface, 3.3);

var_dump($reader->read(0)->calculateVoltage());
$interface->close();