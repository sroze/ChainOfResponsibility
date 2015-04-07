<?php
namespace SRIO\ChainOfResponsibility\Tests;

use Prophecy\Argument;
use SRIO\ChainOfResponsibility\ChainBuilder;
use SRIO\ChainOfResponsibility\ChainContext;
use SRIO\ChainOfResponsibility\ChainRunner;
use SRIO\ChainOfResponsibility\Tests\Fixtures\DependentProcess;
use SRIO\ChainOfResponsibility\Tests\Fixtures\LambdaProcess;

class ChainBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutDependentChainProcesses()
    {
        $builder = new ChainBuilder([
            new LambdaProcess('foo'),
            new LambdaProcess('bar')
        ]);

        $context = $builder->getRunner()->run();
        $this->assertArrayHasKey('foo', $context);
        $this->assertArrayHasKey('bar', $context);
    }

    /**
     * @expectedException \SRIO\ChainOfResponsibility\Exception\UnresolvedDependencyException
     */
    public function testDependencyNotFound()
    {
        (new ChainBuilder([
            new DependentProcess('p1', ['foo']),
            new LambdaProcess('foo')
        ]))->getRunner();
    }

    /**
     * @expectedException \SRIO\ChainOfResponsibility\Exception\CircularDependencyException
     */
    public function testCircularDependencyBetweenProcesses()
    {
        (new ChainBuilder([
            new DependentProcess('p1', ['p2']),
            new DependentProcess('p2', ['p1'])
        ]))->getRunner();
    }

    public function testDependenciesInTheRightOrderOfExecution()
    {
        $order = [];
        $orderTracer = function($context, $name) use (&$order) {
            $order[] = $name;
        };

        (new ChainBuilder([
            new DependentProcess('p2', ['p1'], $orderTracer),
            new DependentProcess('p1', [], $orderTracer)
        ]))->getRunner()->run();

        $this->assertEquals(['p1', 'p2'], $order);
    }

    public function testMultipleDependencies()
    {
        $order = [];
        $orderTracer = function($context, $name) use (&$order) {
            $order[] = $name;
        };

        (new ChainBuilder([
            new DependentProcess('p4', ['p1', 'p3'], $orderTracer),
            new DependentProcess('p2', ['p1'], $orderTracer),
            new DependentProcess('p1', ['p6'], $orderTracer),
            new DependentProcess('p3', [], $orderTracer),
            new DependentProcess('p5', ['p6'], $orderTracer),
            new DependentProcess('p6', [], $orderTracer)
        ]))->getRunner()->run();

        $this->assertEquals(['p6', 'p1', 'p3', 'p4', 'p2', 'p5'], $order);
    }

    public function testOptionalDependencies()
    {
        $order = [];
        $orderTracer = function($context, $name) use (&$order) {
            $order[] = $name;
        };

        (new ChainBuilder([
            new DependentProcess('p2', ['p1'], $orderTracer),
            new DependentProcess('p1', [
                ['p3', false]
            ], $orderTracer)
        ]))->getRunner()->run();

        $this->assertEquals(['p1', 'p2'], $order);
    }
}
