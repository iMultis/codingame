<?php

namespace Multis\CodeBusters\Action;

class Release extends AbstractAction
{
    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return "{$this->getType()}\n";
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return self::TYPE_RELEASE;
    }
}
