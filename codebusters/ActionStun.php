<?php

class ActionStun extends Action
{
    /** @var Buster */
    private $target;

    public function __toString()
    {
        return "{$this->getType()} {$this->target}";
    }

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

    /**
     * @return $this
     */
    private function sortPretenders()
    {
        uasort($this->pretenders, function($buster1, $buster2) {
            return $this->distance($buster1->getX(), $buster1->getY(), $this->base_x, $this->base_y) - $this->distance($buster2->getX(), $buster2->getY(), $this->base_x, $this->base_y);
        });

        return $this;
    }
}
