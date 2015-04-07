<?php
namespace SRIO\ChainOfResponsibility;

use PlasmaConduit\DependencyGraph;
use PlasmaConduit\dependencygraph\DependencyGraphNode;
use PlasmaConduit\either\Left;
use SRIO\ChainOfResponsibility\Exception\CircularDependencyException;
use SRIO\ChainOfResponsibility\Exception\UnresolvedDependencyException;

final class ChainBuilder extends ProcessCollection
{
    /**
     * Name of the root node in dependency graph.
     *
     * @var string
     */
    const ROOT_NODE_NAME = '_root';

    /**
     * @param array $processes
     */
    public function __construct(array $processes)
    {
        $this->add($processes);
    }

    /**
     * Get runner for given processes.
     *
     * @return ChainRunner
     * @throws UnresolvedDependencyException
     */
    public function getRunner()
    {
        return new ChainRunner($this->getOrderedProcesses());
    }

    /**
     * Get processes ordered based on their dependencies.
     *
     * @return array
     * @throws CircularDependencyException
     * @throws UnresolvedDependencyException
     */
    public function getOrderedProcesses()
    {
        $graph = new DependencyGraph();
        $root = new DependencyGraphNode(self::ROOT_NODE_NAME);
        $graph->addRoot($root);

        $processes = $this->getProcesses();
        $nodes = [];
        foreach ($processes as $process) {
            $processName = $this->getProcessName($process);
            $node = new DependencyGraphNode($processName);
            $graph->addDependency($root, $node);
            $nodes[$processName] = [$process, $node];
        }

        foreach ($nodes as $processName => $nodeDescription) {
            list($process, $node) = $nodeDescription;
            if (!$process instanceof DependentChainProcessInterface) {
                $graph->addDependency($root, $node);
                continue;
            }

            foreach ($process->dependsOn() as $dependencyName) {
                if (!array_key_exists($dependencyName, $nodes)) {
                    throw new UnresolvedDependencyException(sprintf(
                        'Process "%s" is dependent of "%s" which is not found',
                        $processName,
                        $dependencyName
                    ));
                }

                if ($graph->addDependency($node, $nodes[$dependencyName][1]) instanceof Left) {
                    throw new CircularDependencyException(sprintf(
                        'Circular dependency found: %s already depends on %s',
                        $dependencyName, $processName
                    ));
                }
            }
        }

        return array_map(function($nodeName) use ($nodes) {
            return $nodes[$nodeName][0];
        }, array_filter($graph->flatten(), function($nodeName) {
            return $nodeName !== self::ROOT_NODE_NAME;
        }));
    }

    /**
     * @param ChainProcessInterface $process
     * @return string
     */
    private function getProcessName(ChainProcessInterface $process)
    {
        return $process instanceof DependentChainProcessInterface ? $process->getName() : spl_object_hash($process);
    }
}
