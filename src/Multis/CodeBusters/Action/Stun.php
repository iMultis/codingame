<?php

namespace Multis\CodeBusters\Action;

use Multis\CodeBusters\Entity\Buster;

class Stun extends AbstractAction
{
    /** @var Buster|null */
    private $targetBuster;

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return "{$this->getType()} {$this->targetBuster->getId()}\n";
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return self::TYPE_STUN;
    }

    /**
     * @return Buster|null
     */
    public function getTargetBuster(): ?Buster
    {
        return $this->targetBuster;
    }

    /**
     * @param Buster|null $targetBuster
     * @return $this
     */
    public function setTargetBuster(?Buster $targetBuster): Stun
    {
        $this->targetBuster = $targetBuster;

        return $this;
    }
}
