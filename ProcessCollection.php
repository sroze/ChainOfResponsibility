<?php

namespace SRIO\ChainOfResponsibility;

class ProcessCollection
{
    /**
     * @var ChainProcessInterface[]
     */
    private $processes;

    /**
     * Add a process to the collection.
     *
     * @param array|ChainProcessInterface $process
     */
    public function add($process)
    {
        if (is_array($process)) {
            foreach ($process as $p) {
                $this->add($p);
            }
        } elseif (!$process instanceof ChainProcessInterface) {
            throw new \RuntimeException(sprintf(
                'Expect to be instance of ChainProcessInterface or array but got %s',
                get_class($process)
            ));
        } else {
            $this->processes[] = $process;
        }
    }

    /**
     * @return ChainProcessInterface[]
     */
    public function getProcesses()
    {
        return $this->processes;
    }
}
