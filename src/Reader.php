<?php
namespace Volantus\MCP3008;

use Volantus\BerrySpi\SpiInterface;

/**
 * Class Reader
 *
 * @package Volantus\MCP3008
 */
class Reader
{
    /**
     * @var SpiInterface
     */
    private $spiInterface;

    /**
     * @var float
     */
    private $baseVoltage;

    /**
     * Reader constructor.
     *
     * @param SpiInterface $spiInterface
     * @param float        $baseVoltage
     */
    public function __construct(SpiInterface $spiInterface, float $baseVoltage = 3.3)
    {
        $this->spiInterface = $spiInterface;
        $this->baseVoltage = $baseVoltage;
    }

    /**
     * @param int $channel
     *
     * @return Measurement
     */
    public function read(int $channel): Measurement
    {

    }
}