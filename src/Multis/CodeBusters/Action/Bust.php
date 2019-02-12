<?php

namespace Multis\CodeBusters\Action;

use Multis\CodeBusters\Entity\Ghost;

class Bust extends AbstractAction
{
    /** @var Ghost|null */
    private $ghost;

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return "{$this->getType()} {$this->ghost->getId()}\n";
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return self::TYPE_BUST;
    }

    /**
     * @return Ghost|null
     */
    public function getGhost(): ?Ghost
    {
        return $this->ghost;
    }

    /**
     * @param Ghost|null $ghost
     * @return $this
     */
    public function setGhost(?Ghost $ghost): Bust
    {
        $this->ghost = $ghost;

        return $this;
    }
}
