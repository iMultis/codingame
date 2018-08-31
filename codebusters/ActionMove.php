<?php

class ActionMove extends Action
{
    /** @var int */
    private $x;

    /** @var int */
    private $y;

    public function __toString()
    {
        return "{$this->getType()} {$this->x} {$this->y}";
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return Action::TYPE_MOVE;
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
}
