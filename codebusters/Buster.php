<?php

class Buster {
    const STATE_EMPTY = 0;
    const STATE_CARRYING_GHOST = 1;
    const STATE_STUNNED = 2;

    /** @var int */
    private $id;

    /** @var int */
    private $team;

    /** @var int */
    private $x;

    /** @var int */
    private $y;

    /** @var int */
    private $type;

    /** @var int */
    private $state;

    /** @var int */
    private $value;

    /** @var int */
    private $target_x;

    /** @var int */
    private $target_y;

    /** @var int */
    private $charge_cooldown = 0;

    /** @var bool */
    private $visible;

    /** @var Action */
    private $action;

    /** @var Action[] */
    private $pretended_actions = [];

    public function __construct($id, $team)
    {
        $this->id = $id;
        $this->team = $team;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param int $team
     * @return $this
     */
    public function setTeam($team)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     * @return $this
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     * @return $this
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param int $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getTargetX()
    {
        return $this->target_x;
    }

    /**
     * @param int $x
     * @return $this
     */
    public function setTargetX($x)
    {
        $this->target_x = $x;

        return $this;
    }

    /**
     * @return int
     */
    public function getTargetY()
    {
        return $this->target_y;
    }

    /**
     * @param int $y
     * @return $this
     */
    public function setTargetY($y)
    {
        $this->target_y = $y;

        return $this;
    }

    /**
     * @return void
     */
    public function cancelTarget()
    {
        $this->target_x = null;
        $this->target_y = null;
    }

    /**
     * @return int
     */
    public function isChargeCooldown()
    {
        return $this->charge_cooldown != 0;
    }

    /**
     * @return $this
     */
    public function decreaseChargeCooldown()
    {
        $this->charge_cooldown -= $this->isChargeCooldown() ? 1 : 0;

        return $this;
    }

    /**
     * @return $this
     */
    public function chargeUsed()
    {
        $this->charge_cooldown = 20;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @param boolean $visible
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = (boolean) $visible;

        return $this;
    }

    /**
     * @return Action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param Action $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return Action[]
     */
    public function getPretendedActions()
    {
        return $this->pretended_actions;
    }

    /**
     * @param Action $pretended_action
     * @return $this
     */
    public function addPretendedAction($pretended_action)
    {
        if (!in_array($pretended_action, $this->pretended_actions)) {
            if (!in_array($this, $pretended_action->getPretenders())) {
                $pretended_action->addPretender($this);
            }

            $this->pretended_actions[] = $pretended_action;
        }

        return $this;
    }

    /**
     * @param Action $pretended_action
     * @return $this
     */
    public function removePretendedAction($pretended_action)
    {
        $key = array_search($pretended_action, $this->pretended_actions);

        if ($key !== false) {
            unset($this->pretended_actions[$key]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function sortPretendedActions()
    {
        uasort($this->pretended_actions, function ($action1, $action2) {
            if ($action1::TYPE != $action2::TYPE) {
                if ($action1::TYPE == Action::TYPE_RELEASE || $action2::TYPE == Action::TYPE_RELEASE) {
                    return $action1::TYPE == Action::TYPE_RELEASE ? 1 : -1;
                }

                if ($action1::TYPE == Action::TYPE_STUN || $action2::TYPE == Action::TYPE_STUN) {
                    return $action1::TYPE == Action::TYPE_STUN ? 1 : -1;
                }

                if ($action1::TYPE == Action::TYPE_BUST || $action2::TYPE == Action::TYPE_BUST) {
                    return $action1::TYPE == Action::TYPE_BUST ? 1 : -1;
                }
            } else {
                if ($action1::TYPE == Action::TYPE_RELEASE) {
                    return 0;
                }

                if ($action1::TYPE == Action::TYPE_STUN || $action1::TYPE == Action::TYPE_BUST) {
                    return array_search($this, $action1->sortPretenders()->getPretenders()) - array_search($this, $action2->sortPretenders()->getPretenders());
                }
            }
        });

        return $this;
    }
}
