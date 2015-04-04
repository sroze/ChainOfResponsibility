<?php
namespace SRIO\ChainOfResponsibility;

interface DependentChainProcessInterface extends ChainProcessInterface
{
    public function getName();
    public function dependsOn();
}
