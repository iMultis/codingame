<?php

namespace Multis\CodeBusters\Action;

use Multis\CodeBusters\Entity\Buster;

abstract class AbstractAction
{
    const TYPE_MOVE = 'MOVE';
    const TYPE_BUST = 'BUST';
    const TYPE_STUN = 'STUN';
    const TYPE_RELEASE = 'RELEASE';

    const MOVE_SPEED = 800;
    const BUST_MIN_DISTANCE = 900;
    const BUST_MAX_DISTANCE = 1760;
    const RELEASE_DISTANCE = 1600;
    const STUN_DURATION = 10;
    const STUN_COOL_DOWN = 20;
    const STUN_DISTANCE = 1760;

    /** @var Buster|null */
    private $selectedBuster;

    /** @var Buster[] */
    protected $busters = [];

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return string
     */
    abstract public function __toString(): string;

    /**
     * @return bool
     */
    public function isMove(): bool
    {
        return $this->getType() == self::TYPE_MOVE;
    }

    /**
     * @return bool
     */
    public function isBust(): bool
    {
        return $this->getType() == self::TYPE_BUST;
    }

    /**
     * @return bool
     */
    public function isStun(): bool
    {
        return $this->getType() == self::TYPE_STUN;
    }

    /**
     * @return bool
     */
    public function isRelease(): bool
    {
        return $this->getType() == self::TYPE_RELEASE;
    }

    /**
     * @return Buster|null
     */
    public function getSelectedBuster(): ?Buster
    {
        return $this->selectedBuster;
    }

    /**
     * @param Buster|null $buster
     * @return $this
     */
    public function setSelectedBuster(?Buster $buster): AbstractAction
    {
        $this->selectedBuster = $buster;

        if ($buster->getSelectedAction() !== $this) {
            $buster->setSelectedAction($this);
        }

        return $this;
    }

    /**
     * @return Buster[]
     */
    public function getBusters(): array
    {
        return $this->busters;
    }

    /**
     * @param Buster $buster
     * @return $this
     */
    public function addBuster(Buster $buster): AbstractAction
    {
        if (!in_array($buster, $this->busters)) {
            $this->busters[] = $buster;

            if (!in_array($this, $buster->getActions())) {
                $buster->addAction($this);
            }
        }

        return $this;
    }

    /**
     * @param Buster $buster
     * @return $this
     */
    public function removeBuster(Buster $buster): AbstractAction
    {
        $key = array_search($buster, $this->busters);

        if ($key !== false) {
            unset($this->busters[$key]);
            $buster->removeAction($this);
        }

        return $this;
    }
}
