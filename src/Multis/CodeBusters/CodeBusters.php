<?php

namespace Multis\CodeBusters;

use Multis\Lib\IApplication;

class CodeBusters implements IApplication
{
    /**
     * @inheritdoc
     */
    public function execute(): void
    {
        $dataReader = new DataReader();
        $dataReader->readInitialData();

        $state = null;
        $AI = new AI();

        while (true) {
            $state = $dataReader->readTurnData($state);

            foreach ($AI->getActions($state) as $action) {
                echo $action;
            }
        }
    }
}
