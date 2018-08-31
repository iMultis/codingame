<?php

class ActionRelease extends Action
{
    public function __toString()
    {
        return "{$this->getType()}";
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return Action::TYPE_RELEASE;
    }
}
