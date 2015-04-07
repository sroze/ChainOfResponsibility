<?php
namespace SRIO\ChainOfResponsibility;

class ChainRunner extends ProcessCollection
{
    /**
     * @var DecoratorFactoryInterface
     */
    private $decoratorFactory;

    /**
     * @param ChainProcessInterface[] $processes
     * @param DecoratorFactoryInterface|null $decoratorFactory
     */
    public function __construct(array $processes, DecoratorFactoryInterface $decoratorFactory = null)
    {
        $this->decoratorFactory = $decoratorFactory !== null ? $decoratorFactory : new DecoratorFactory();
        $this->add($processes);
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
        $processes = $this->getProcesses();
        $numberOfProcesses = count($processes);
        if ($numberOfProcesses === 0) {
            throw new \RuntimeException('You must have at least one process to run');
        }

        $decoratedProcesses = [];
        $next = null;

        for ($i = $numberOfProcesses - 1; $i >= 0; $i--) {
            $decoratedProcesses[$i] = $next = $this->decoratorFactory->decorate($processes[$i], $next);
        }

        return $decoratedProcesses;
    }
}
