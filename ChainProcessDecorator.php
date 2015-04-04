<?php
namespace SRIO\ChainOfResponsibility;

final class ChainProcessDecorator implements ChainProcessInterface
{
    /**
     * @var ChainProcessInterface
     */
    private $process;

    /**
     * @var ChainProcessInterface
     */
    private $nextProcess;

    /**
     * @param ChainProcessInterface $process
     * @param ChainProcessInterface $nextProcess
     */
    public function __construct(ChainProcessInterface $process, ChainProcessInterface $nextProcess = null)
    {
        $this->process = $process;
        $this->nextProcess = $nextProcess;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ChainContext $context)
    {
        $this->process->execute($context);

        if (null !== $this->nextProcess) {
            $this->nextProcess->execute($context);
        }
    }
}
