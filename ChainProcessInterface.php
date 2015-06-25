<?php

namespace SRIO\ChainOfResponsibility;

interface ChainProcessInterface
{
    /**
     * Start the processing.
     *
     * The `ChainContext` argument might contain information populated by previous
     * chain processes. You can write into to share information with the next chain
     * process.
     *
     * @param ChainContext $context
     */
    public function execute(ChainContext $context);
}
