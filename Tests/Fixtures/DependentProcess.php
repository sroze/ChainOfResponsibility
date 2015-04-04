<?php
namespace SRIO\ChainOfResponsibility\Tests\Fixtures;

use SRIO\ChainOfResponsibility\ChainContext;
use SRIO\ChainOfResponsibility\DependentChainProcessInterface;

class DependentProcess implements DependentChainProcessInterface
{
    private $name;
    private $dependencies;
    private $callable;

    public function __construct($name, $dependencies = [], callable $callable = null)
    {
        $this->name = $name;
        $this->dependencies = $dependencies;
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ChainContext $context)
    {
        if (null !== ($callable = $this->callable)) {
            $callable($context, $this->name);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function dependsOn()
    {
        return $this->dependencies;
    }
}
