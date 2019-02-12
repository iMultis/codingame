<?php

namespace Multis\CodeBusters;

use Multis\CodeBusters\Action\AbstractAction;
use Multis\CodeBusters\Action\Bust;
use Multis\CodeBusters\Action\Move;
use Multis\CodeBusters\Action\Release;
use Multis\CodeBusters\Action\Stun;
use Multis\CodeBusters\Entity\AbstractBuster;
use Multis\Lib\Math;

class AI
{
    /** @var State */
    protected $state;

    /**
     * @param State $state
     * @return AbstractAction[]
     */
    public function getActions(State $state): array
    {
        $this->state = $state;
        $actions = [];

        // That is a prototype. The following code is to be optimised a lot
        $possibleActions = array_merge(
            $this->getPossibleReleases(),
            $this->getPossibleStuns(),
            $this->getPossibleBusts()
        );

        foreach ($this->state->getMyBusters() as $buster) {
            if (!$buster->getSelectedAction()) {
                if ($buster->getState() == AbstractBuster::STATE_CARRYING_GHOST) {
                    $action = new Move();
                    $action
                        ->setX($this->state->getMyBaseX())
                        ->setY($this->state->getMyBaseY())
                        ->setSelectedBuster($buster)
                    ;
                } else {
                    /** @var AbstractAction $action */
                    foreach ($buster->getActions() as $action) {
                        if (
                            $action->isStun()
                            && !$action->getSelectedBuster()
                            && !$buster->getSelectedAction()
                        ) {
                            $buster->setSelectedAction($action);
                        }
                    }

                    if (!$buster->getSelectedAction()) {
                        /** @var AbstractAction $action */
                        foreach ($buster->getActions() as $action) {
                            if (
                                $action->isBust()
                                && !$action->getSelectedBuster()
                                && !$buster->getSelectedAction()
                            ) {
                                $buster->setSelectedAction($action);
                            }
                        }
                    }

                    if (!$buster->getSelectedAction()) {
                        $action = new Move();
                        $action
                            ->setX(round(rand(0, State::MAP_WIDTH)))
                            ->setY(round(rand(0, State::MAP_HEIGHT)))
                            ->setSelectedBuster($buster)
                        ;
                    }
                }
            }

            $actions[] = $buster->getSelectedAction();
        }

        return $actions;
    }

    /**
     * @return Release[]
     */
    protected function getPossibleReleases(): array
    {
        $actions = [];

        foreach ($this->state->getMyBusters() as $buster) {
            if ($buster->getState() == AbstractBuster::STATE_STUNNED) {
                continue;
            }

            if (
                $buster->getState() == AbstractBuster::STATE_CARRYING_GHOST
                && Math::getDistance($buster->getX(), $buster->getY(), $this->state->getMyBaseX(), $this->state->getMyBaseY()) <= AbstractAction::RELEASE_DISTANCE
            ) {
                $action = new Release();
                $action->setSelectedBuster($buster);
                $actions[] = $action;
            }
        }

        return $actions;
    }

    /**
     * @return Stun[]
     */
    protected function getPossibleStuns(): array
    {
        $actions = [];

        foreach ($this->state->getEnemyBusters() as $enemyBuster) {
            if ($enemyBuster->isVisible()) {
                $action = new Stun();
                $action->setTargetBuster($enemyBuster);

                foreach ($this->state->getMyBusters() as $buster) {
                    if (
                        !$buster->isStunCoolDown()
                        && Math::getDistance($buster->getX(), $buster->getY(), $enemyBuster->getX(), $enemyBuster->getY()) <= AbstractAction::STUN_DISTANCE
                    ) {
                        $action->addBuster($buster);
                    }
                }

                if ($action->getBusters()) {
                    $actions[] = $action;
                }
            }
        }

        return $actions;
    }

    /**
     * @return Bust[]
     */
    protected function getPossibleBusts(): array
    {
        $actions = [];

        foreach ($this->state->getGhosts() as $ghost) {
            if ($ghost->isVisible()) {
                $action = new Bust();
                $action->setGhost($ghost);

                foreach ($this->state->getMyBusters() as $buster) {
                    $distance = Math::getDistance($ghost->getX(), $ghost->getY(), $buster->getX(), $buster->getY());

                    if ($distance > AbstractAction::BUST_MIN_DISTANCE && $distance < AbstractAction::BUST_MAX_DISTANCE) {
                        $action->addBuster($buster);
                    }
                }

                if ($action->getBusters()) {
                    $actions[] = $action;
                }
            }
        }

        return $actions;
    }
}
