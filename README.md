# Chain Of Responsibility

[![Build Status](https://travis-ci.org/sroze/ChainOfResponsibility.svg?branch=master)](https://travis-ci.org/sroze/ChainOfResponsibility)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/7ca0f072-4b1b-47da-b68c-509085366caf/mini.png)](https://insight.sensiolabs.com/projects/7ca0f072-4b1b-47da-b68c-509085366caf)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sroze/ChainOfResponsibility/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sroze/ChainOfResponsibility/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/sroze/ChainOfResponsibility/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sroze/ChainOfResponsibility/?branch=master)

This light library helps to implement quickly a chain of responsibility. This pattern is especially useful when
you need a clear process that involve multiple steps.

## Usage

There's two components:
- [The Runner](#runner) that will take a brunch of processes, decorates them to create the chain-of-responsibility and
  execute the head.
- [The builder](#builder) adds a little bit of like to processes because they can declare a list of other processes that
  needs to be run before.

### Runner

Create your processes classes that implements the `ChainProcessInterface`. This interface only has one `execute` method
that take a `ChainContext` object as parameter. This parameter is usable like an array and will provides your processes
a common way to exchange information between them.

```php
use SRIO\ChainOfResponsibility\ChainContext;
use SRIO\ChainOfResponsibility\ChainProcessInterface;

class LambdaProcess implements ChainProcessInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(ChainContext $context)
    {
        // Do whatever you want in this small process, such as
        // sending a mail, manipulating files, ...
    }
}
```

Create the chain runner with a list of given processes. The list order is quite important since it'll be the order of
execution of processes.

```php
$runner = new ChainRunner([
    new FirstProcess(),
    new SecondProcess(),
    new ThirdProcess()
]);
```

Then, use the `run` method to run each process, with an **optional** `ChainContext` argument.
```php
$runner->run(new ChainContext([
    'foo' => 'bar'
]));
```

## Builder

TODO
