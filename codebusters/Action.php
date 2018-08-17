<?php

class Action
{
    const TYPE_MOVE = 'move';
    const TYPE_BUST = 'bust';
    const TYPE_STUN = 'stun';
    const TYPE_RELEASE = 'release';

    private $executors = [];

    /**
     * @return Buster[]
     */
    public function getExecutors()
    {
        return $this->executors;
    }

    /**
     * @param Buster $executor
     * @return $this
     */
    public function addExecutor($executor)
    {
        $this->executors[] = $executor;

        return $this;
    }
}
