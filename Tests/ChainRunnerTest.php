<?php

namespace SRIO\ChainOfResponsibility\Tests;

use Prophecy\Argument;
use SRIO\ChainOfResponsibility\ArrayChainContext;
use SRIO\ChainOfResponsibility\ChainRunner;
use SRIO\ChainOfResponsibility\Tests\Fixtures\LambdaProcess;

class ChainRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testNoProcesses()
    {
        $runner = new ChainRunner([]);
        $runner->run();
    }

    public function testOneProcess()
    {
        $process = $this->prophesize('SRIO\ChainOfResponsibility\ChainProcessInterface');
        $process->execute(Argument::any())->shouldBeCalled();

        (new ChainRunner([$process->reveal()]))->run();
    }

    public function testMultipleProcessesAreAllCalled()
    {
        $context = (new ChainRunner([
            new LambdaProcess('A'),
            new LambdaProcess('B'),
            new LambdaProcess('C'),
        ]))->run();

        $this->assertArrayHasKey('A', $context);
        $this->assertArrayHasKey('B', $context);
        $this->assertArrayHasKey('C', $context);
    }

    public function testMultipleProcessesAreAllCalledInTheRightOrder()
    {
        $context = (new ChainRunner([
            new LambdaProcess('A'),
            new LambdaProcess('B', function ($context) {
                $this->assertInContext(['A'], $context);
            }),
            new LambdaProcess('C', function ($context) {
                $this->assertInContext(['A', 'B'], $context);
            }),
        ]))->run();

        $this->assertArrayHasKey('A', $context);
        $this->assertArrayHasKey('B', $context);
        $this->assertArrayHasKey('C', $context);
    }

    public function testDefaultContextArrivesToProcess()
    {
        (new ChainRunner([
            new LambdaProcess('C', function ($context) {
                $this->assertInContext(['A', 'B'], $context);
            }),
        ]))->run(new ArrayChainContext([
            'A' => true,
            'B' => false,
        ]));
    }

    private function assertInContext(array $keys, ArrayChainContext $context)
    {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $context);
        }
    }
}
