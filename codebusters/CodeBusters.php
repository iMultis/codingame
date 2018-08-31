<?php

class CodeBusters
{
    private $my_team;
    private $enemy_team;

    private $base_x;
    private $base_y;

    private $busters_per_team = 0;

    /**
     * @var Buster[]
     */
    private $busters = [];

    private $ghosts_count = 0;

    /**
     * @var Action[]
     */
    private $actions = [];

    /**
     * @var Ghost[]
     */
    private $ghosts = [];

    public function __construct()
    {
        fscanf(STDIN, "%d",$this->busters_per_team);
        fscanf(STDIN, "%d",$this->ghosts_count);
        fscanf(STDIN, "%d",$this->my_team);

        $this->enemy_team = $this->my_team == 1 ? 0 : 1;

        if ($this->my_team == 0) {
            $this->base_x = 0;
            $this->base_y = 0;
        } else {
            $this->base_x = 16000;
            $this->base_y = 9000;
        }

        for ($i = 0; $i < $this->busters_per_team * 2; $i++) {
            $this->busters[$i] = new Buster($i, $i < $this->busters_per_team ? 0 : 1);
        }

        for ($i = 0; $i < $this->ghosts_count; $i++) {
            $this->ghosts[$i] = new Ghost($i);
        }
    }

    public function readData()
    {
        fscanf(STDIN, "%d", $entities_count);

        foreach ($this->busters as $buster) {
            $buster->setVisible(false);
        }

        foreach ($this->ghosts as $ghost) {
            $ghost->setVisible(false);
        }

        for ($i = 0; $i < $entities_count; $i++) {
            fscanf(STDIN, "%d %d %d %d %d %d", $entityId, $x, $y, $entityType, $state, $value);
            //error_log($entityId.' '.$x.' '.$y.' '.$entityType.' '.$state.' '.$value);

            if ($entityType == -1) {
                $this->ghosts[$entityId]
                    ->setX($x)
                    ->setY($y)
                    ->setBustersTrappingCount($value)
                    ->setVisible(true)
                ;
            } else {
                $this->busters[$entityId]
                    ->setX($x)
                    ->setY($y)
                    ->setState($state)
                    ->setValue($value)
                    ->decreaseChargeCooldown()
                    ->setVisible(true)
                ;
            }
        }

        foreach ($this->ghosts as $ghost) {
            if (!$ghost->isVisible() && $ghost->getX() && $ghost->getY()) {
                foreach ($this->getBustersByTeam($this->my_team) as $buster) {
                    if ($this->distance($buster->getX(), $buster->getY(), $ghost->getX(), $ghost->getY()) < 1700) {
                        $ghost->setX(null);
                        $ghost->setY(null);
                    }
                }
            }
        }

        foreach ($this->getBustersByTeam($this->my_team) as $buster1) {
            $target_location_visible = false;

            foreach ($this->getBustersByTeam($this->my_team) as $buster2) {
                if ($this->distance($buster1->getTargetX(), $buster1->getTargetY(), $buster2->getX(), $buster2->getY()) < 1400) {
                    $target_location_visible = true;
                    break;
                }
            }

            if ($target_location_visible) {
                $target_present = false;

                foreach ($this->ghosts as $ghost) {
                    if ($ghost->isVisible() && $this->distance($buster1->getTargetX(), $buster1->getTargetY(), $ghost->getX(), $ghost->getY()) < 2200) {
                        $target_present = true;
                    }
                }

                if (!$target_present) {
                    $buster1->setTargetX(null);
                    $buster1->setTargetY(null);
                }
            }
        }
    }

    /**
     * @param $team
     * @return Buster[]
     */
    private function getBustersByTeam($team)
    {
        $busters = [];

        foreach ($this->busters as &$buster) {
            if ($buster->getTeam() == $team) {
                $busters[] = $buster;
            }
        }

        return $busters;
    }

    private function distance($x1, $y1, $x2, $y2)
    {
        return sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2));
    }

    public function getBustableGhost($x, $y)
    {
        error_log('searching for a ghost around '.$x.', '.$y);

        foreach ($this->ghosts as $ghost) {
            if ($ghost->isVisible()) {
                $distance = $this->distance($ghost->getX(), $ghost->getY(), $x, $y);

                if ($distance < 1760 && $distance > 900) {
                    return $ghost;
                }
            }
        }

        return null;
    }

    public function getStunableBuster($x, $y)
    {
        foreach ([Buster::STATE_CARRYING_GHOST, Buster::STATE_EMPTY] as $buster_state) {
            foreach ($this->getBustersByTeam($this->enemy_team) as $buster) {
                $distance = $this->distance($buster->getX(), $buster->getY(), $x, $y);

                if ($distance < 1760 && $buster->getState() == $buster_state) {
                    return $buster;
                }
            }
        }

        return null;
    }

    private function sortGhostsByDistance($x, $y)
    {
        /*foreach ($this->ghosts as $ghost) {
            if ($ghost->getX() && $ghost->getY()) {
                $closest_ghost = $ghost;
                break;
            }
        }

        if (!empty($closest_ghost)) {
            $min_distance = $this->distance($closest_ghost->getX(), $closest_ghost->getY(), $x, $y);

            foreach ($this->ghosts as $ghost) {
                if ($ghost->getX() && $ghost->getY() && $this->distance($ghost->getX(), $ghost->getY(), $x, $y) < $min_distance) {
                    $closest_ghost = $ghost;
                }
            }

            return $closest_ghost;
        }

        return null;*/

        $ghosts = [];

        foreach ($this->ghosts as $ghost) {
            if ($ghost->getX() && $ghost->getY()) {
                $ghosts[] = $ghost;
            }
        }

        uasort($ghosts, function ($ghost1, $ghost2) use ($x, $y) {
            return $this->distance($ghost1->getX(), $ghost1->getY(), $x, $y) - $this->distance($ghost2->getX(), $ghost2->getY(), $x, $y);
        });

        return $ghosts;
    }

    /**
     * @param Buster $buster
     */
    private function findNewTarget(&$buster)
    {
        $ghosts = $this->sortGhostsByDistance($buster->getX(), $buster->getY());

        if (count($ghosts)) {
            foreach ($ghosts as $ghost) {
//                $
//                foreach ($this->getBustersByTeam($this->my_team) as $buster) {
//                    if
//                }
            }

            $buster->setTargetX($ghost->getX());
            $buster->setTargetY($ghost->getY());
        } else {
            $buster->setTargetX(rand(0, 16000));
            $buster->setTargetY(rand(0, 9000));
        }
    }

    public function hunt()
    {
        $this->arrangeActions();

        uasort($this->actions, function ($action1, $action2) {
            if ($action1->getType() == Action::TYPE_RELEASE && $action2->getType() == Action::TYPE_RELEASE) {
                return 0;
            } elseif ($action1->getType() == Action::TYPE_RELEASE || $action2->getType() == Action::TYPE_RELEASE) {
                return $action1->getType() == Action::TYPE_RELEASE ? 1 : -1;
            }

            if ($action1->getType() == Action::TYPE_STUN && $action2->getType() == Action::TYPE_STUN) {
                return 0;
            } elseif ($action1->getType() == Action::TYPE_STUN || $action2->getType() == Action::TYPE_STUN) {
                return $action1->getType() == Action::TYPE_STUN ? 1 : -1;
            }

            if ($action1->getType() == Action::TYPE_BUST && $action2->getType() == Action::TYPE_BUST) {
                return 0;
            } elseif ($action1->getType() == Action::TYPE_BUST || $action2->getType() == Action::TYPE_BUST) {
                return $action1->getType() == Action::TYPE_BUST ? 1 : -1;
            }

            return 0;
        });





        foreach ($this->getBustersByTeam($this->my_team) as $buster) {
            $buster->sortPretendedActions();
            $buster->setAction(reset($buster->getPretendedActions()));
        }

        foreach ($this->getBustersByTeam($this->my_team) as $buster) {
            echo $buster->getAction();
        }

        /*foreach ($this->getBustersByTeam($this->my_team) as $buster) {
            $stunable_buster = $this->getStunableBuster($buster->getX(), $buster->getY());

            if (!$buster->isChargeCooldown() && $stunable_buster) {
                echo "STUN " . $stunable_buster->getId() . "\n";
                $buster->chargeUsed();
            } elseif ($buster->getState() == Buster::STATE_CARRYING_GHOST) {
                if ($this->distance($buster->getX(), $buster->getY(), $this->base_x, $this->base_y) < 1600) {
                    echo "RELEASE\n";
                } else {
                    echo "MOVE " . $this->base_x . " " . $this->base_y . "\n";
                }
            } else {
                $ghost = $this->getBustableGhost($buster->getX(), $buster->getY());

                if ($ghost) {
                    echo "BUST " . $ghost->getId() . "\n";
                    $buster->cancelTarget();
                } else {
                    error_log('no ghosts found');

                    if (
                        !$buster->getTargetX() || !$buster->getTargetY()
                        || $this->distance($buster->getX(), $buster->getY(), $buster->getTargetX(), $buster->getTargetY()) < 800
                    ) {
                        error_log('looking for a new target');
                        $this->findNewTarget($buster);
                    }

                    error_log('moving to (' . $buster->getTargetX() . ', ' . $buster->getTargetY() . ')');
                    echo "MOVE " . $buster->getTargetX() . " " . $buster->getTargetY() . "\n";
                }
            }
        }*/
    }

    /**
     * @param Action $action
     */
    private function addAction($action)
    {
        switch ($action->getType()) {
            case Action::TYPE_RELEASE:
                $this->actions[] = $action;
                break;

            case Action::TYPE_BUST:
            case Action::TYPE_STUN:
                foreach ($this->actions as $cur_action) {
                    if ($cur_action->getType() == $action->getType() && $cur_action->getTarget() == $action->getTarget()) {
                        foreach ($action->getPretenders() as $pretender) {
                            $cur_action->addPretender($pretender);
                        }
                    }
                }
                break;
        }
    }

    private function arrangeActions()
    {
        foreach ($this->getBustersByTeam($this->my_team) as $buster) {
            if ($buster->getState() == Buster::STATE_CARRYING_GHOST && $this->distance($buster->getX(), $buster->getY(), $this->base_x, $this->base_y) < 1600) {
                $action = new ActionRelease();
                $action->setExecutor($buster);

                $this->addAction($action);
            }

            if (!$buster->isChargeCooldown()) {
                foreach ($this->getBustersByTeam($this->enemy_team) as $enemy_buster) {
                    $distance = $this->distance($buster->getX(), $buster->getY(), $enemy_buster->getX(), $enemy_buster->getY());

                    if ($distance < 1760) {
                        $action = new ActionStun();
                        $action
                            ->addPretender($buster)
                            ->setTarget($enemy_buster)
                        ;

                        $this->addAction($action);
                    }
                }
            }

            foreach ($this->ghosts as $ghost) {
                if ($ghost->isVisible()) {
                    $distance = $this->distance($buster->getX(), $buster->getY(), $ghost->getX(), $ghost->getY());

                    if ($distance < 1760 && $distance > 900) {
                        $action = new ActionBust();
                        $action
                            ->addPretender($buster)
                            ->setTarget($ghost)
                        ;

                        $this->addAction($action);
                    }
                }
            }
        }
    }
}
