<?php

namespace Multis\CodeBusters;

use Multis\CodeBusters\Action\AbstractAction;
use Multis\CodeBusters\Entity\AbstractEntity;
use Multis\CodeBusters\Entity\Buster;
use Multis\CodeBusters\Entity\Ghost;
use Multis\Lib\IState;

class State implements IState
{
    const MAP_WIDTH = 16001;
    const MAP_HEIGHT = 9001;

    /** @var int */
    private $myTeamId;

    /** @var Ghost[] */
    private $ghosts = [];

    /** @var Buster[] */
    private $myBusters = [];

    /** @var Buster[] */
    private $enemyBusters = [];

    /**
     * State constructor.
     *
     * @param int $myTeamId
     * @param int $bustersPerTeam
     * @param int $ghostsCount
     */
    public function __construct(int $myTeamId, int $bustersPerTeam, int $ghostsCount)
    {
        $this->myTeamId = $myTeamId;

        for ($i = 0; $i < $bustersPerTeam * 2; $i++) {
            $buster = new Buster($i, $i < $bustersPerTeam ? 0 : 1);

            if ($buster->getTeam() == $this->myTeamId) {
                $this->myBusters[] = $buster;
            } else {
                $this->enemyBusters[] = $buster;
            }
        }

        for ($i = 0; $i < $ghostsCount; $i++) {
            $this->ghosts[] = new Ghost($i);
        }
    }

    /**
     * @return int
     */
    public function getMyTeamId(): int
    {
        return $this->myTeamId;
    }

    /**
     * @return int
     */
    public function getMyBaseX(): int
    {
        return $this->myTeamId == 0 ? 0 : self::MAP_WIDTH;
    }

    /**
     * @return int
     */
    public function getMyBaseY(): int
    {
        return $this->myTeamId == 0 ? 0 : self::MAP_HEIGHT;
    }

    /**
     * @return int
     */
    public function getEnemyBaseX(): int
    {
        return $this->myTeamId == 0 ? 0 : self::MAP_WIDTH;
    }

    /**
     * @return int
     */
    public function getEnemyBaseY(): int
    {
        return $this->myTeamId == 0 ? 0 : self::MAP_HEIGHT;
    }

    /**
     * @return Ghost[]
     */
    public function getGhosts(): array
    {
        return $this->ghosts;
    }

    /**
     * @param int $id
     * @return Ghost
     * @throws \Exception
     */
    public function getGhostById(int $id): Ghost
    {
        foreach ($this->ghosts as $ghost) {
            if ($ghost->getId() == $id) {
                return $ghost;
            }
        }

        throw new \Exception("Ghost with id {$id} was not found");
    }

    /**
     * @return Buster[]
     */
    public function getBusters(): array
    {
        return array_merge($this->myBusters, $this->enemyBusters);
    }

    /**
     * @return Buster[]
     */
    public function getMyBusters(): array
    {
        return $this->myBusters;
    }

    /**
     * @return Buster[]
     */
    public function getEnemyBusters(): array
    {
        return $this->enemyBusters;
    }

    /**
     * @param int $id
     * @return Buster
     * @throws \Exception
     */
    public function getBusterById(int $id): Buster
    {
        foreach ($this->getBusters() as $buster) {
            if ($buster->getId() == $id) {
                return $buster;
            }
        }

        throw new \Exception("Buster with id {$id} was not found");
    }

    /**
     * @return AbstractEntity[]
     */
    public function getEntities(): array
    {
        return array_merge($this->myBusters, $this->enemyBusters, $this->ghosts);
    }
}
