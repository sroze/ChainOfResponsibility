<?php

namespace SRIO\ChainOfResponsibility;

interface DependentChainProcessInterface extends NamedChainProcessInterface
{
    /**
     * Return an array of name of processes on which the current process depends.
     *
     * This will ensure that these processes will be run before the current one.
     *
     * @return array
     */
    public function dependsOn();
}
