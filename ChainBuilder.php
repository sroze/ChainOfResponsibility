<?php
namespace SRIO\ChainOfResponsibility;

final class ChainBuilder
{
    private $processes = [];

    public function add(ChainProcessInterface $chainProcess)
    {
        $this->processes[] = $chainProcess;
    }

    public function getChainRunner()
    {

    }
}
