<?php

namespace Multis\Lib;

interface IDataReader
{
    /**
     * Read initial data (once per game)
     *
     * @return void
     */
    public function readInitialData(): void;

    /**
     * Read data for the next turn/iteration
     *
     * @param IState|null $state
     * @return IState
     */
    public function readTurnData(?IState $state): IState;
}
