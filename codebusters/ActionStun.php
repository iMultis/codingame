<?php

class ActionStun extends Action
{
    const TYPE = Action::TYPE_STUN;

    /**
     * @var Buster
     */
    private $target;

    /**
     * @return Buster
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param Buster $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }
}
