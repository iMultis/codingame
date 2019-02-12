<?php

namespace Multis\CodeBusters\Action;

class Move extends AbstractAction
{
    /** @var null|int */
    private $x;

    /** @var null|int */
    private $y;

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return "{$this->getType()} {$this->x} {$this->y}\n";
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return self::TYPE_MOVE;
    }

    /**
     * @return null|int
     */
    public function getX(): ?int
    {
        return $this->x;
    }

    /**
     * @param null|int $x
     * @return $this
     */
    public function setX(?int $x): Move
    {
        $this->x = $x;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getY(): ?int
    {
        return $this->y;
    }

    /**
     * @param null|int $y
     * @return $this
     */
    public function setY(?int $y): Move
    {
        $this->y = $y;

        return $this;
    }
}
