<?php

class Buster {
    const STATE_EMPTY = 0;
    const STATE_CARRYING_GHOST = 1;
    const STATE_STUNNED = 2;

    private $id;
    private $team;
    private $x;
    private $y;
    private $type;
    private $state;
    private $value;
    private $target_x;
    private $target_y;
    private $charge_cooldown = 0;

    public function __construct($id, $team)
    {
        $this->id = $id;
        $this->team = $team;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     * @return $this
     */
    public function setTeam($team)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     * @return $this
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     * @return $this
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetX()
    {
        return $this->target_x;
    }

    /**
     * @param mixed $x
     * @return $this
     */
    public function setTargetX($x)
    {
        $this->target_x = $x;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetY()
    {
        return $this->target_y;
    }

    /**
     * @param mixed $y
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

}