<?php

namespace Multis\CodeBusters\Entity;

use Multis\CodeBusters\Action\AbstractAction;

class Buster extends AbstractBuster
{
    /** @var null|int */
    private $targetX;

    /** @var null|int */
    private $targetY;

    /** @var int */
    private $stunCoolDown = 0;

    /** @var Action|null */
    private $selectedAction;

    /** @var Action[] */
    private $actions = [];

    /**
     * @return null|int
     */
    public function getTargetX(): ?int
    {
        return $this->targetX;
    }

    /**
     * @param null|int $x
     * @return $this
     */
    public function setTargetX(?int $x): Buster
    {
        $this->targetX = $x;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getTargetY(): ?int
    {
        return $this->targetY;
    }

    /**
     * @param null|int $y
     * @return $this
     */
    public function setTargetY(?int $y): Buster
    {
        $this->targetY = $y;

        return $this;
    }

    /**
     * @return int
     */
    public function getStunCoolDown(): int
    {
        return $this->stunCoolDown;
    }

    /**
     * @return bool
     */
    public function isStunCoolDown(): bool
    {
        return $this->stunCoolDown != 0;
    }

    /**
     * @param int $stunCoolDown
     * @return Buster
     */
    public function setStunCoolDown(int $stunCoolDown): Buster
    {
        $this->stunCoolDown = $stunCoolDown;

        return $this;
    }

    /**
     * @return AbstractAction|null
     */
    public function getSelectedAction(): ?AbstractAction
    {
        return $this->selectedAction;
    }

    /**
     * @param AbstractAction|null $selectedAction
     * @return $this
     */
    public function setSelectedAction(?AbstractAction $selectedAction): Buster
    {
        $this->selectedAction = $selectedAction;

        if ($selectedAction->getSelectedBuster() !== $this) {
            $selectedAction->setSelectedBuster($this);
        }

        return $this;
    }

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param AbstractAction $action
     * @return $this
     */
    public function addAction(AbstractAction $action): Buster
    {
        if (!in_array($action, $this->actions)) {
            $this->actions[] = $action;

            if (!in_array($this, $action->getBusters())) {
                $action->addBuster($this);
            }
        }

        return $this;
    }

    /**
     * @param AbstractAction $action
     * @return $this
     */
    public function removeAction(AbstractAction $action): Buster
    {
        $key = array_search($action, $this->actions);

        if ($key !== false) {
            unset($this->actions[$key]);
            $action->removeBuster($this);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function postTurnCallback(): void
    {
        parent::postTurnCallback();

        if ($this->isStunCoolDown()) {
            $this->stunCoolDown--;
        }

        $this->actions = [];
        $this->selectedAction = null;
    }
}
