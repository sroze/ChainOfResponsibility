<?php

namespace SRIO\ChainOfResponsibility;

interface DecoratorFactoryInterface
{
    /**
     * @param ChainProcessInterface $process
     * @param ChainProcessInterface $next
     */
    public function decorate(ChainProcessInterface $process, ChainProcessInterface $next = null);
}
