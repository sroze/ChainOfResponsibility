<?php
namespace SRIO\ChainOfResponsibility;

interface NamedChainProcessInterface extends ChainProcessInterface
{
    /**
     * Returns the unique name of a process.
     *
     * This will be used by other processes to explicitly depends on other processes
     * identified by their names.
     *
     * @return string
     */
    public function getName();
}
