<?php

class ActionBust extends Action
{
    const TYPE = Action::TYPE_BUST;

    /**
     * @var Ghost
     */
    private $target;

    /**
     * @return Ghost
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param Ghost $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }
}
