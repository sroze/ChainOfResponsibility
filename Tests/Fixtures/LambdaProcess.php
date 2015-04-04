<?php
namespace SRIO\ChainOfResponsibility\Tests\Fixtures;

use SRIO\ChainOfResponsibility\ChainContext;
use SRIO\ChainOfResponsibility\ChainProcessInterface;

class LambdaProcess implements ChainProcessInterface
{
    private $variable;
    private $callable;

    public function __construct($variable, callable $callable = null)
    {
        $this->variable = $variable;
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ChainContext $context)
    {
        if (null !== ($callable = $this->callable)) {
            $callable($context);
        }

        $context[$this->variable] = true;
    }
}
