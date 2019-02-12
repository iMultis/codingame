<?php

namespace Multis\CodeBusters\Entity;

abstract class AbstractEntity
{
    const TYPE_GHOST = -1;

    /** @var int */
    protected $id;

    /** @var null|int */
    protected $x;

    /** @var null|int */
    protected $y;

    /** @var bool */
    protected $visible = false;

    /**
     * Entity constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getX(): ?int
    {
        return $this->x;
    }

    /**
     * @param int|null $x
     * @return $this
     */
    public function setX(?int $x): AbstractEntity
    {
        $this->x = $x;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getY(): ?int
    {
        return $this->y;
    }

    /**
     * @param int|null $y
     * @return $this
     */
    public function setY(?int $y): AbstractEntity
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return $this
     */
    public function setVisible(bool $visible): AbstractEntity
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return void
     */
    public function preTurnCallback(): void
    {
        $this->setVisible(false);
    }

    /**
     * @return void
     */
    public function postTurnCallback(): void
    {
    }
}
