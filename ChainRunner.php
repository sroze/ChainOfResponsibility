<?php
namespace SRIO\ChainOfResponsibility;

class ChainRunner 
{
    /**
     * @var ChainProcessInterface[]
     */
    private $processes;

    /**
     * @param ChainProcessInterface[] $processes
     */
    public function __construct(array $processes)
    {
        $this->processes = $processes;
    }

    /**
     * @param ChainContext $context
     * @return ChainContext
     */
    public function run(ChainContext $context = null)
    {
        if (null === $context) {
            $context = new ChainContext();
        }

        $this->getHead()->execute($context);
        return $context;
    }

    /**
     * @return ChainProcessInterface
     */
    public function getHead()
    {
        return $this->getDecoratedProcesses()[0];
    }

    /**
     * @return ChainProcessInterface[]
     */
    public function getDecoratedProcesses()
    {
        if (count($this->processes) === 0) {
            throw new \RuntimeException('You must have at least one process to run');
        }

        $decoratedProcesses = [];
        $numberOfProcesses = count($this->processes);
        $next = null;

        for ($i = $numberOfProcesses - 1; $i >= 0; $i--) {
            $decoratedProcesses[$i] = $next = new ChainProcessDecorator($this->processes[$i], $next);
        }

        return $decoratedProcesses;
    }
}
