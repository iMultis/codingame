<?php

class Ghost {
    private $id;
    private $x;
    private $y;
    private $busters_trapping_count;
    private $visible;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     * @return $this
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     * @return $this
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBustersTrappingCount()
    {
        return $this->busters_trapping_count;
    }

    /**
     * @param mixed $busters_trapping_count
     * @return $this
     */
    public function setBustersTrappingCount($busters_trapping_count)
    {
        $this->busters_trapping_count = $busters_trapping_count;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @param boolean $visible
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = (boolean) $visible;

        return $this;
    }
}