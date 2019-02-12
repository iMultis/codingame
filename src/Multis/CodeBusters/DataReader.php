<?php

namespace Multis\CodeBusters;

use Multis\CodeBusters\Entity\AbstractEntity;
use Multis\Lib\IDataReader;
use Multis\Lib\IState;

class DataReader implements IDataReader
{
    /** @var bool */
    protected $debug;

    /** @var int */
    private $bustersPerTeam = 0;

    /** @var int */
    private $ghostsCount = 0;

    /** @var int */
    private $myTeamId = 0;

    /**
     * DataReader constructor.
     *
     * @param bool $debug
     */
    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * @inheritdoc
     */
    public function readInitialData(): void
    {
        fscanf(STDIN, '%d',$this->bustersPerTeam);
        fscanf(STDIN, '%d',$this->ghostsCount);
        fscanf(STDIN, '%d',$this->myTeamId);
    }

    /**
     * @inheritdoc
     */
    public function readTurnData(?IState $state): IState
    {
        if (!$state) {
            $state = new State($this->myTeamId, $this->bustersPerTeam, $this->ghostsCount);
        }

        foreach ($state->getEntities() as $entity) {
            $entity->preTurnCallback();
        }

        fscanf(STDIN, '%d', $entitiesCount);

        for ($i = 0; $i < $entitiesCount; $i++) {
            fscanf(STDIN, '%d %d %d %d %d %d', $entityId, $x, $y, $entityType, $entityState, $value);

            if ($entityType == AbstractEntity::TYPE_GHOST) {
                $state->getGhostById($entityId)
                    ->setX($x)
                    ->setY($y)
                    ->setVisible(true)
                ;
            } else {
                $state->getBusterById($entityId)
                    ->setX($x)
                    ->setY($y)
                    ->setState($entityState)
                    ->setValue($value)
                    ->setVisible(true)
                ;
            }
        }

        foreach ($state->getEntities() as $entity) {
            $entity->postTurnCallback();
        }

        return $state;
    }
}
