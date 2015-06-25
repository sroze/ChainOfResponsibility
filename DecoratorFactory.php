<?php

namespace SRIO\ChainOfResponsibility;

class DecoratorFactory implements DecoratorFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function decorate(ChainProcessInterface $process, ChainProcessInterface $next = null)
    {
        return new ChainProcessDecorator($process, $next);
    }
}
