<?php

class Action
{
    const TYPE_MOVE = 'MOVE';
    const TYPE_BUST = 'BUST';
    const TYPE_STUN = 'STUN';
    const TYPE_RELEASE = 'RELEASE';

    /** @var int */
    protected $type;

    /** @var Buster */
    private $executor;

    /** @var Buster[] */
    protected $pretenders = [];

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Buster
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     * @param Buster $executor
     * @return $this
     */
    public function setExecutor($executor)
    {
        $executor->setAction($this);
        $this->executor = $executor;

        return $this;
    }

    /**
     * @return Buster[]
     */
    public function getPretenders()
    {
        return $this->pretenders;
    }

    /**
     * @param Buster $pretender
     * @return $this
     */
    public function addPretender($pretender)
    {
        if (!in_array($pretender, $this->pretenders)) {
            if (!in_array($this, $pretender->getPretendedActions())) {
                $pretender->addPretendedAction($this);
            }

            $this->pretenders[] = $pretender;
        }
        
        return $this;
    }

    /**
     * @param Buster $pretender
     * @return $this
     */
    public function removePretender($pretender)
    {
        $key = array_search($pretender, $this->pretenders);

        if ($key !== false) {
            unset($this->pretenders[$key]);
        }

        return $this;
    }
}
