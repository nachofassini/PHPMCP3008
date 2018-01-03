<?php
namespace Volantus\MCP3008\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Volantus\BerrySpi\SpiInterface;
use Volantus\MCP3008\Reader;

/**
 * Class ReaderTest
 *
 * @package Volantus\MCP3008\Tests
 */
class ReaderTest extends TestCase
{
    /**
     * @var SpiInterface|MockObject
     */
    private $spiInterface;

    /**
     * @var Reader
     */
    private $reader;

    protected function setUp()
    {
        $this->spiInterface = $this->getMockBuilder(SpiInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['isOpen', 'open', 'close', 'transfer'])
            ->getMock();
        $this->spiInterface->method('isOpen')->willReturn(true);
        $this->reader = new Reader($this->spiInterface, 3.3);
    }

    public function test_construct_openedSpiDevice()
    {
        $this->spiInterface = $this->getMockBuilder(SpiInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['isOpen', 'open'])
            ->getMock();
        $this->spiInterface->method('isOpen')->willReturn(false);

        $this->spiInterface->expects(self::once())
            ->method('open');

        $this->reader = new Reader($this->spiInterface, 3.3);
    }

    /**
     * @expectedException \Volantus\MCP3008\InvalidChannelException
     * @expectedExceptionMessage Invalid channel given => only channel between 0-7 supported
     */
    public function test_read_badChannel()
    {
        $this->reader->read(8);
    }

    /**
     * @expectedException \Volantus\MCP3008\InvalidSpiDataException
     * @expectedExceptionMessage Received bad binary data via SPI => 01000000020000000300000004000000, expected 3 words but received 4
     */
    public function test_read_invalidSpiData()
    {
        $this->spiInterface->expects(self::once())
            ->method('transfer')
            ->with(self::equalTo(192))
            ->willReturn(pack('I*', 1, 2, 3, 4));

        $this->reader->read(4);
    }

    public function test_read_correct()
    {
        $this->spiInterface->expects(self::once())
            ->method('transfer')
            ->with(self::equalTo(208))
            ->willReturn(pack('I*', 0, 512, 16));

        $result =$this->reader->read(5);
        self::assertEquals(5, $result->getChannel());
        self::assertEquals(3.3, $result->getRefVoltage());
        self::assertEquals(512, $result->getRawValue());
    }
}