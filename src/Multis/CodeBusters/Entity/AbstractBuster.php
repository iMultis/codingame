<?php

namespace Multis\CodeBusters\Entity;

abstract class AbstractBuster extends AbstractEntity {
    const STATE_UNKNOWN = null;
    const STATE_EMPTY = 0;
    const STATE_CARRYING_GHOST = 1;
    const STATE_STUNNED = 2;

    /** @var int */
    protected $team;

    /** @var null|int */
    protected $state;

    /** @var null|int */
    protected $value;

    public function __construct(int $id, int $team)
    {
        parent::__construct($id);
        $this->team = $team;
    }

    /**
     * @return int
     */
    public function getTeam(): int
    {
        return $this->team;
    }

    /**
     * @return null|int
     */
    public function getState(): ?int
    {
        return $this->state;
    }

    /**
     * @param null|int $state
     * @return $this
     */
    public function setState(?int $state): AbstractBuster
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * @param null|int $value
     * @return $this
     */
    public function setValue(?int $value): AbstractBuster
    {
        $this->value = $value;

        return $this;
    }
}
